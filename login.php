<DOCTYPE html>
    <html lang="en">

    <head>

    </head>

    <body>

    </body>



    <!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>
    <link rel="stylesheet" href="static/style.css">

</head>

<style>
    .fa-lock,
    .fa-unlock {
        cursor: pointer;
    }

    .incorrect {
        box-shadow: 0px 0px 0px 3px rgb(255, 0, 0, 0.5) !important;
        border: 1px solid red !important;
    }
</style> 

<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#">CALENDAR<b></b></a>
        </div>
        <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Log in to start your session</p>
            <form id="login-form">
                <div class="input-group mb-3">
                    <input type="text" maxlength="50" name="user_login" class="form-control"
                        placeholder="Email or Login id" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="user_pass" class="form-control" maxlength="15" placeholder="Password"
                        required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock password-toggler"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-block">Log In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p> -->
            <p class="mb-0 mt-3">
                <a href="register.php" class="text-center">Register a new membership</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
    </div>
    <!-- /.login-box -->

    <script>
        // toggle show/hide password in password fields
        let toggleButtons = document.querySelectorAll('.password-toggler');
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                let inputElement = e.target.closest('.input-group.mb-3').querySelector('input');
                if (inputElement.type === "password") {
                    inputElement.type = "text";
                    button.classList.remove('fa-lock');
                    button.classList.add('fa-unlock');
                } else {
                    inputElement.type = "password";
                    button.classList.remove('fa-unlock');
                    button.classList.add('fa-lock');
                }
            })
        })

        // submit login form on through ajax

        let form = document.querySelector('#login-form');
        let btnSubmit = form.querySelector('#btn-submit');

        form.addEventListener('submit', e => {
            e.preventDefault();

            btnSubmit.textContent = "Logging in....";
            btnSubmit.disabled = true;

            let url = "php_processing/do_ajax_login.php";
            let formData = new FormData(form);

            fetch(url, {
                method: "POST",
                body: formData,
            })
                .then(response => {
                    return response.json();
                },
                    (err) => {
                        alert("No Internet Connection");
                    }).then(jsonData => {

                        if (jsonData.error) {
                            if (jsonData.incorrect_field && jsonData.incorrect_field != "") {
                                form.querySelector(`input[name=${jsonData.incorrect_field}]`).classList.add('incorrect');
                            }
                            alert(jsonData['error-message']);
                        } else {
                            form.reset();
                            alert("login successfull. Click OK to continue.");
                            location.replace("index.php");

                        }
                    }).catch(err => {
                        alert("Login Failed! Please try again later");
                    }).finally(() => {
                        btnSubmit.disabled = false;
                        btnSubmit.textContent = "Log In";
                    })
        })

        // remove invalid class styling from form input fields if they are set due to invalid data
        let inputs = form.querySelectorAll('input');
        Array.from(inputs).forEach(input => {
            input.addEventListener('input', () => {
                input.classList.remove('incorrect');
            })
        })
    </script>

    </body>

    </html>