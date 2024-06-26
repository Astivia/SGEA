const inputCurp=document.getElementById('participante-curp');
const mensajeError=document.getElementById('mensaje-error');

inputCurp.addEventListener('input',function()
{
    const longitud=inputCurp.ariaValueMax.length;
    if(longitud < 18 || longitud > 18){
        mensajeError.style.display='inline';
    }else{
        mensajeError.style.display='none'};
    
});