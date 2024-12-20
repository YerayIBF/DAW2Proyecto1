document.addEventListener("DOMContentLoaded", () => {
    const dynamicContent = document.getElementById("dynamic-content");
    const sidebarLinks = document.querySelectorAll(".sidebar nav a");

    sidebarLinks.forEach(link => {
        link.addEventListener("click", async (e) => {
            e.preventDefault();
            const view = link.getAttribute("link");

            sidebarLinks.forEach(link => link.classList.remove("active"));
            link.classList.add("active");

            try {
                const response = await fetch(`?controller=api&action=${view}`);
                if (!response.ok) throw new Error("Error al cargar la vista");
                const html = await response.text();

                dynamicContent.innerHTML = html;

                if (view === "pedidos") {
                   
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
                      
                }
            } catch (error) {
                console.error("Error al cargar la vista:", error);
                dynamicContent.innerHTML = `<p>Error al cargar la vista: ${view}</p>`;
            }
        });
    });

 
  
});
