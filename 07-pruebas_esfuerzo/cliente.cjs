const http = require('http');
const { performance } = require('perf_hooks');

async function enviarSolicitud() {
    return new Promise((resolve) => {
        const inicioReq = performance.now();
        const req = http.get('http://localhost:3000', (res) => {
            res.on('data', () => {});
            res.on('end', () => {
                const finReq = performance.now();
                resolve({ status: res.statusCode, duration: finReq - inicioReq });
            });
        });
        req.on('error', () => {
            const finReq = performance.now();
            resolve({ status: 500, duration: finReq - inicioReq }); // Cuenta como error si falla
        });
    });
}

async function probarServidor() {
    const cantidad = 4950; // <--- CAMBIA ESTE VALOR PARA CADA PRUEBA (100, 200, 500, 1000, 1001)[cite: 8]
    console.log(`Iniciando prueba con ${cantidad} solicitudes concurrentes...`);
    
    const inicioTotal = performance.now();

    const resultados = await Promise.all(
        Array.from({ length: cantidad }, () => enviarSolicitud())
    );

    const finTotal = performance.now();
    const tiempoTotal = finTotal - inicioTotal;

    // Métricas
    const solicitadas = resultados.length;
    const exitosas = resultados.filter(r => r.status === 200).length;
    const errores = solicitadas - exitosas;

    // Calcular la mediana individual de los tiempos
    const duraciones = resultados.map(r => r.duration).sort((a, b) => a - b);
    const mitad = Math.floor(duraciones.length / 2);
    const mediana = duraciones.length % 2 !== 0 ? duraciones[mitad] : (duraciones[mitad - 1] + duraciones[mitad]) / 2;

    console.log(`Solicitudes enviadas: ${solicitadas}`);
    console.log(`Respuestas exitosas: ${exitosas}`);
    console.log(`Errores: ${errores}`);
    console.log(`Tiempo total: ${tiempoTotal.toFixed(2)} ms`);
    console.log(`Mediana individual: ${mediana.toFixed(2)} ms`);
}

probarServidor();