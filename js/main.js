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
                    const ofertasSection = document.querySelector("#ofertas");
                    if (ofertasSection) {
                        ofertasSection.innerHTML = "";

                    } if (!document.querySelector(`script[src="js/ofertaAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/ofertaAdmin.js";
                        script.onload = async () => {
                            const ofertaAdmin = new OfertaAdmin();
                            await ofertaAdmin.init();
                        };
                        document.body.appendChild(script);
                    } else {
                        const ofertaAdmin = new OfertaAdmin();
                        await ofertaAdmin.init();
                    }
                }

                if (view === "logs") {
                    const logsSection = document.querySelector("#logs");
                    if (logsSection) {
                        logsSection.innerHTML = "";

                    } if (!document.querySelector(`script[src="js/logAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/logAdmin.js";
                        script.onload = async () => {
                            const logAdmin = new LogsAdmin();
                            await logAdmin.init();
                        };
                        document.body.appendChild(script);
                    } else {
                        const logAdmin = new LogsAdmin();
                        await logAdmin.init();
                    }
                }
                

                if (view === "usuarios") {
                    const usuariosSection = document.querySelector("#usuarios");
                    if (usuariosSection) {
                    
                        usuariosSection.innerHTML = "";
                    }

                    if (!document.querySelector(`script[src="js/usuarioAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/usuarioAdmin.js";
                        script.onload = async () => {
                            const usuarioAdmin = new UsuarioAdmin();
                            await usuarioAdmin.init();
                        };
                        document.body.appendChild(script);
                    } else {
                        const usuarioAdmin = new UsuarioAdmin();
                        await usuarioAdmin.init();
                    }
                }


                if (view === "productos") {

                    if (!document.querySelector(`script[src="js/currencyConverter.js"]`)) {
                        const currencyScript = document.createElement("script");
                        currencyScript.src = "js/currencyConverter.js";
                        document.body.appendChild(currencyScript);
                    }
                    const productosSection = document.querySelector("#productos");
                    if (productosSection) {
                    
                        productosSection.innerHTML = "";
                    }

                    if (!document.querySelector(`script[src="js/productoAdmin.js"]`)) {
                        const script = document.createElement("script");
                        script.src = "js/productoAdmin.js";
                        script.onload = async () => {
                            const productoAdmin = new ProductoAdmin();
                            await productoAdmin.init();
                        };
                        document.body.appendChild(script);
                    } else {
                        const productoAdmin = new ProductoAdmin();
                        await productoAdmin.init();
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
