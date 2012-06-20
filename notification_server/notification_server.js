var http = require('http'),
    queryString = require('querystring'),
    httpServer = http.createServer(serverHandler).listen(8080),
    io = require('socket.io').listen(httpServer),
    sessions = new Array();

io.sockets.on('connection', function(socket){

    socket.on('login', function(data){

        initSession( socket, data );

    });

    socket.on('desconectar', function(dados){

        if( sessions[ dados.sid ] ) {

            socket.disconnect();
            destroySession( dados.sid );

        }

    });

});

function initSession( socket, data ) {

    sessions[ data.sid ] = {
        socket: socket,
        username: data.username
    };

    console.log('Usuário ' + data.username + ' conectou do servidor de notificações!');

}

function destroySession( sid ) {

    console.log('Usuário ' + sessions[ sid ].username + ' desconectou do servidor de notificações!');

    delete sessions[ sid ];

}

function emitNotification(data) {

    if ( sessions[ data.sid ] ) {

        sessions[ data.sid ].socket.emit( 'notificacao', data );

    }

}

function serverHandler(request, response) {
    if(
        request.method === 'POST' &&
        request.connection.remoteAddress == '127.0.0.1'
    ) {

        var requestBufferData = '';

        request.on('data', function(data){

            requestBufferData += data;

        });

        request.on('end', function(){

            broadcastNotifications( response, queryString.parse( requestBufferData ) );

        });

    } else {

        request.setEncoding('utf8');
        response.writeHead(403, {'content-Type': 'text/html'});
        response.end(
            '<html><head><meta charset="utf-8"><title>403 Proíbido</title></head>' +
            '<body><h1>403: Não é permitido acessar este servidor.</h1></body></html>'
        );

    }
}

function broadcastNotifications(response, postData){

    var chaveSeguranca = '22cda956cf8a821c1d11ad5b8d3a6f6f708a755f';

    if (
        postData.chaveSeguranca &&
        postData.chaveSeguranca === chaveSeguranca &&
        postData.dadosJson
       ) {

        var jsonData = JSON.parse( postData.dadosJson );

        if ( Array.isArray( jsonData ) ) {

            for (var i = 0; i < jsonData.length; i++) {

                emitNotification( jsonData[i] );

            }

        } else {

            emitNotification( jsonData );

        }

        response.writeHead(200, {'content-Type': 'application/json'});

        response.end(JSON.stringify({
            success: true
        }));

    } else {

        response.writeHead(403, {'content-Type': 'application/json'});

        response.end(JSON.stringify({
            success: false
        }));

    }

}