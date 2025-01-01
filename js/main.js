document.addEventListener("DOMContentLoaded", () => {
    const dynamicContent = document.getElementById("dynamic-content");
    const sidebarLinks = document.querySelectorAll(".sidebar nav a");

    sidebarLinks.forEach(link => {
        link.addEventListener("click", async (e) => {
            if (link.id === "btn-volver") return;
            e.preventDefault();
            const view = link.getAttribute("link");

            sidebarLinks.forEach(link => link.classList.remove("active"));
            link.classList.add("active");

            try {
                const response = await fetch(`?controller=api&action=${view}`);
                if (!response.ok) throw new Error("Error al cargar la vista");
                const html = await response.text();

                dynamicContent.innerHTML = html;

                if (view === "ofertas") {
                    if (!document.querySelector(`script[src="js/ofertaAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/ofertaAdmin.js";
                        script.onload = async () => {
                            const admin = new ofertaAdmin();
                            await admin.init();
                        };
                        document.body.appendChild(script);
                    } else {
                        const admin = new ofertaAdmin();
                        await admin.init();
                    }
                }

                if (view === "pedidos") {

                    if (!document.querySelector(`script[src="js/currencyConverter.js"]`)) {
                        const currencyScript = document.createElement("script");
                        currencyScript.src = "js/currencyConverter.js";
                        document.body.appendChild(currencyScript);
                    }
                    
                    const pedidosSection = document.querySelector("#pedidos");
                    if (pedidosSection) {
                    
                        pedidosSection.innerHTML = "";
                    }

             
                    if (!document.querySelector(`script[src="js/pedidoAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/pedidoAdmin.js";
                        script.onload = async () => {
                            const pedidoAdmin = new PedidoAdmin();
                            await pedidoAdmin.init();
                        };
                        document.body.appendChild(script);
                    } else {
                        const pedidoAdmin = new PedidoAdmin();
                        await pedidoAdmin.init();
                    }
                      
                }else if (view === "detallesPedidos") {
                    const detallePedidoSection = document.querySelector("#detallePedido");
                    if (detallePedidoSection) {
                        detallePedidoSection.innerHTML = "";
                    }
                
                    if (!document.querySelector(`script[src="js/DetallePedidoAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/DetallePedidoAdmin.js";
                        script.onload = async () => {
                            const detallePedidoAdmin = new DetallePedidoAdmin();
                         
                           
                            const idPedido = urlParams.get('id');
                            if (idPedido) {
                                await detallePedidoAdmin.init(idPedido);
                            }
                        };
                        document.body.appendChild(script);
                    } else {
                        const detallePedidoAdmin = new DetallePedidoAdmin();
                       
                        const idPedido = urlParams.get('id');
                        if (idPedido) {
                            await detallePedidoAdmin.init(idPedido);
                        }
                    }
                }

                
                
                
                
            } catch (error) {
                console.error("Error al cargar la vista:", error);
                dynamicContent.innerHTML = `<p>Error al cargar la vista: ${view}</p>`;
            }
        });
    });

 
  
});
