function showLogin() {
    document.getElementById("welcome").style.display = "none";
    document.getElementById("login").style.display = "block";
}

// Cambia a la sección seleccionada
function navigateToSection(section) {
    document.querySelectorAll("section").forEach((sec) => {
        sec.style.display = "none";
    });
    if (section) {
        document.getElementById(section).style.display = "block";
    }
}

// Cargar funciones al iniciar
window.onload = function() {
    // Muestra el contenido principal solo si el usuario ha iniciado sesión
    if (sessionStorage.getItem("loggedIn") === "true") {
        document.getElementById("content").style.display = "block";
    }
};
// Función para mostrar secciones del panel de administración
function navigateToAdminSection(section) {
    document.querySelectorAll("section").forEach((sec) => {
        sec.style.display = "none";
    });
    if (section) {
        document.getElementById(section).style.display = "block";
    }
}

// Cargar contenido del chat para administración
async function loadAdminChat() {
    const response = await fetch("chat.php");
    const messages = await response.json();
    const chatContentAdmin = document.getElementById("chat-messages-admin");
    chatContentAdmin.innerHTML = "";
    messages.forEach((msg) => {
        chatContentAdmin.innerHTML += <p><strong>${msg.usuario}:</strong> ${msg.mensaje}</p>;
    });
}

// Llamada a funciones de carga de contenido al iniciar
window.onload = function() {
    loadBlog();
    loadMateriales();
    loadAdminChat();
};
document.addEventListener("DOMContentLoaded", function () {
    function cargarMensajes() {
        fetch('chat.php')
            .then(response => response.json())
            .then(data => {
                let chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = data.map(msg => <p><strong>${msg.nombre}</strong>: ${msg.mensaje}</p>).join('');
            });
    }

    function cargarBlogs() {
        fetch('blog.php')
            .then(response => response.json())
            .then(data => {
                let blogContent = document.getElementById('blog-content');
                blogContent.innerHTML = data.map(blog => <h3>${blog.titulo}</h3><p>${blog.contenido}</p><small>Publicado por ${blog.nombre}</small>).join('');
            });
    }

    setInterval(cargarMensajes, 3000);
    setInterval(cargarBlogs, 3000);
});
document.addEventListener("DOMContentLoaded", function () {
    function cargarMensajes() {
        fetch('chat.php')
            .then(response => response.json())
            .then(data => {
                let chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = data.map(msg => <p><strong>${msg.nombre}</strong>: ${msg.mensaje}</p>).join('');
            });
    }

    function cargarBlogs() {
        fetch('blog.php')
            .then(response => response.json())
            .then(data => {
                let blogContent = document.getElementById('blog-content');
                blogContent.innerHTML = data.map(blog => <h3>${blog.titulo}</h3><p>${blog.contenido}</p><small>Publicado por ${blog.nombre}</small>).join('');
            });
    }

    setInterval(cargarMensajes, 3000);
    setInterval(cargarBlogs, 3000);
});
