$(function() {
    $("#submit").on("click", function() {
        $("#login").validate({
            rules: {
                email: {required: true, email: true},
                password: {required: true}
            },
            messages: {
                email: {required: "Introduce tu email", email: "Introduce un email válido"},
                password: {required: "Introduce tu contraseña"}
            }
        });
    });
});