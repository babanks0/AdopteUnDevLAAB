"use strict";

// Class definition
var KTSignupGeneral = function() {
    // Elements
    var form,formEntreprise;
    var submitButton,submitButtonEntreprise;
    var validator,validatorEntreprise;
    var passwordMeter;
       
    // Handle form
    var handleForm  = function(e) 
    {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        // FormValidation.validators.validePhone = isValidPhone;
        validator = FormValidation.formValidation(
			form,
			{
				fields: {
					'firstName': {
						validators: {
							notEmpty: {
								message: _FirstName_Required
							}
						}
                    },
                    'lastName': {
						validators: {
							notEmpty: {
								message: _LastName_Required
							}
						}
					},
					'email': {
                        validators: {
							notEmpty: {
								message: _Email_NotEmpty_Connexion
							},
                            emailAddress: {
								message: _Email_EmailAddress
							}
						}
                    },
                    'password': {
                        validators: {
                            notEmpty: {
                                message: _Password_Required
                            },
                            callback: {
                                message: _Password_Valid,
                                callback: function(input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirmPassword': {
                        validators: {
                            notEmpty: {
                                message: _Password_Confirm
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: _Password_Confirm
                            }
                        }
                    },
                    'customCheck1': {
                        validators: {
                            notEmpty: {
                                message: _Condition_Confirm
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }  
                    }),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
				}
			}
		);

        validatorEntreprise = FormValidation.formValidation(
			formEntreprise,
			{
				fields: {
					'name': {
						validators: {
							notEmpty: {
								message: _FirstName_Required
							}
						}
                    },
					'email': {
                        validators: {
							notEmpty: {
								message: _Email_NotEmpty_Connexion
							},
                            emailAddress: {
								message: _Email_EmailAddress
							}
						}
                    },
                    'password': {
                        validators: {
                            notEmpty: {
                                message: _Password_Required
                            },
                            callback: {
                                message: _Password_Valid,
                                callback: function(input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirmPassword': {
                        validators: {
                            notEmpty: {
                                message: _Password_Confirm
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: _Password_Confirm
                            }
                        }
                    },
                    'customCheck1': {
                        validators: {
                            notEmpty: {
                                message: _Condition_Confirm
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }  
                    }),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
				}
			}
		);

        function handleFormValidation(validator, form, submitButton) {
            validator.revalidateField('password');
        
            validator.validate().then(function (status) {
                if (status === 'Valid') {
                    // Afficher l'indicateur de chargement
                    submitButton.setAttribute('data-kt-indicator', 'on');
        
                    // Désactiver le bouton pour éviter plusieurs clics
                    submitButton.disabled = true;
        
                    // Simuler une requête Ajax
                    var data = $(form).serializeArray();
        
                    $.ajax({
                        url: register_url,
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                // Afficher une alerte de succès
                                Swal.fire({
                                    text: response.msg,
                                    icon: "success",
                                    buttonsStyling: false,
                                    allowOutsideClick: false,
                                    confirmButtonText: _Form_Ok_Swal_Button_Text_Notification,
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        form.reset(); // Réinitialiser le formulaire
                                        // window.location.href = response.url;
                                    }
                                });
                            } else if (response.status === 'exist') {
                                // Afficher une alerte d'erreur
                                Swal.fire({
                                    text: response.msg,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: _Form_Ok_Swal_Button_Text_Notification,
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
        
                                // Réactiver le bouton
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                            } else {
                                // Afficher une alerte d'erreur générale
                                Swal.fire({
                                    text: _Account_Error,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: _Form_Ok_Swal_Button_Text_Notification,
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
        
                                // Réactiver le bouton
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                            }
                        },
                        error: function () {
                            // Afficher une alerte en cas d'erreur de requête
                            Swal.fire({
                                text: "Une erreur est survenue, veuillez patienter et réessayer",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
        
                            // Réactiver le bouton
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    });
                } else {
                    // Afficher une alerte si la validation échoue
                    Swal.fire({
                        text: _Form_Error_Swal_Notification,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: _Form_Ok_Swal_Button_Text_Notification,
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
        
                    // Réactiver le bouton
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                }
            });
        }
        

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            handleFormValidation(validator, form, submitButton);

        });

        submitButtonEntreprise.addEventListener('click', function (e) {
            e.preventDefault();

            handleFormValidation(validatorEntreprise, formEntreprise, submitButtonEntreprise);

        });

        // Handle password input
        // form.querySelector('input[name="password"]').addEventListener('input', function() {
        //     if (this.value.length > 0) {
        //         validator.updateFieldStatus('password', 'NotValidated');
        //     }
        // });
    }

    

    // Public functions
    return {
        // Initialization
        init: function() {
            // Elements
            form = document.querySelector('#kt_sign_up_form');
            formEntreprise = document.querySelector('#kt_sign_up_form_entreprise');
            submitButton = document.querySelector('#kt_sign_up_submit');
            submitButtonEntreprise = document.querySelector('#kt_sign_up_submit_entreprise');
            // passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

            handleForm ();
        }
    };
}();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function () {
    KTSignupGeneral.init();
});
