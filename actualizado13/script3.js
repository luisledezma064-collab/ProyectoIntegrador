document.addEventListener("DOMContentLoaded", () => {

    cargarTarjetas();

    const form = document.getElementById("payment-form");

    if(form){
        form.addEventListener("submit", guardarTarjeta);
    }

});


function cargarTarjetas(){

    fetch("obtener_tarjeta.php")
    .then(res => res.json())
    .then(data => {

        const lista = document.getElementById("cards-list");

        lista.innerHTML = "";

        if(data.length === 0){
            lista.innerHTML = "<p>No hay métodos guardados</p>";
            return;
        }

        data.forEach(t => {

            lista.innerHTML += `
            <div style="border:1px solid #ddd;padding:10px;margin-bottom:10px;border-radius:8px;display:flex;justify-content:space-between;">
                
                <span>
                    💳 **** **** **** ${t.numero_tarjeta}
                </span>

                <button onclick="eliminarTarjeta(${t.id_metodo})">
                    Eliminar
                </button>

            </div>
            `;

        });

    });

}


function guardarTarjeta(e){

    e.preventDefault();

    const formData = new FormData(e.target);

    fetch("guardar_tarjeta.php",{
        method:"POST",
        body:formData
    })
    .then(res => res.text())
    .then(data => {

        if(data.trim() === "ok"){

            alert("Tarjeta guardada");

            e.target.reset();

            cargarTarjetas();

        }else{

            alert("Error: "+data);

        }

    });

}


function eliminarTarjeta(id){

    if(!confirm("¿Eliminar tarjeta?")) return;

    fetch("eliminar_tarjeta.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"id="+id
    })
    .then(res => res.text())
    .then(() => cargarTarjetas());

}
const tarjetaInput = document.getElementById("numero_tarjeta");

tarjetaInput.addEventListener("input", function(){

    // eliminar todo lo que no sea número
    let valor = this.value.replace(/\D/g,"");

    // limitar a 16 dígitos
    valor = valor.substring(0,16);

    // formatear en bloques de 4
    valor = valor.replace(/(.{4})/g,"$1 ").trim();

    this.value = valor;

});
function mostrarToast(mensaje){

const toast = document.getElementById("toast");

toast.textContent = mensaje;

toast.classList.add("show");

setTimeout(()=>{
toast.classList.remove("show");
},3000);

}