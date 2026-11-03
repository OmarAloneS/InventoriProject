const http = require('http');

const server = http.createServer((req, res) => {
    res.writeHead(200, { 'Content-Type': 'text/plain' });
    res.end('Respuesta exitosa desde Inventori');
});

server.listen(3000, () => {
    console.log('Servidor de pruebas corriendo en http://localhost:3000');
});