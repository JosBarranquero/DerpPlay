$.validator.addMethod('name', function(value, element) {
    return this.optional(element) || /^[a-zA-Z ]+$/.test(value);
});

$(function() {
    $("#submit").on("click", function() {
        $("#signup").validate({
            rules: {
                username: {required: true, name: true},
                email: {required: true, email: true},
                password: {required: true, minlength: 8},
                passwordConf: {required: true, equalTo: "#password"}
            },
            messages: {
                username: {required: "Introduce un nombre de usuario", name: "El nombre de usuario solo puede contener carácteres alfabéticos"},
                email: {required: "Introduce tu email", email: "Introduce un email válido"},
                password: {required: "Introduce una contraseña", minlength: "La contraseña debe tener un mínimo de 8 carácteres"},
                passwordConf: {required: "Debes confirmar la contraseña", equalTo: "Las contraseñas no coinciden"}
            }
        });
    });
});