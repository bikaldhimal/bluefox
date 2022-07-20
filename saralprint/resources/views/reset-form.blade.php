<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Forget password</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="" />
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: "Lato";
                font-size: 1.1rem;
            }
            body {
                font-family: "Lato";
            }
            .container {
                /* background-color: aquamarine; */
                height: 100vh;
                width: 100vw;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .inner-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 33%;
                padding: 1.5em;
                border: 1px solid rgba(220, 210, 210, 0.4);
                outline-offset: 15px;
                box-shadow: 5px 10px 5px rgb(233, 233, 233);
                transition: 1sec;
            }
            form {
                display: flex;
                flex-direction: column;
                align-items: start;
                gap: 0.5em;
            }
            .input-container {
                display: flex;
                flex-direction: column;
                align-items: start;
                gap: 0.32em;
            }
            label {
                font-weight: 500;
            }
            input {
                width: 18rem;
                height: 2.25rem;
                padding-left: 0.5em;
                font-size: 0.8rem;
                border: 1px solid rgb(233, 233, 233);
                border-radius: 0.5rem;
            }
            button {
                border: none;
                height: 2rem;
                width: 10em;
                margin-top: 1em;
                background-color: rgb(50, 126, 248);
                text-align: center;
                border-radius: 0.7rem;
                font-weight: 200;
                color: white;
                font-size: 0.7rem;
            }
            button:hover {
                background-color: rgba(50, 126, 248, 0.9);
            }
            ::placeholder {
                color: rgb(215, 210, 210);
                font-size: 0.9rem;
            }
            @media (min-width: 640px) {
                * {
                    font-size: 1.3rem;
                    /* background-color: aqua; */
                }
                button {
                    height: 2.2rem;
                    font-size: 1.02rem;
                    width: 11rem;
                }
                .inner-container {
                    height: 55%;
                    width: 80%;
                }
                input {
                    width: 20rem;
                }
                label {
                    font-size: 1rem;
                }
            }
            @media (min-width: 768px) {
                .inner-container {
                    width: 21em;
                }
                button {
                    font-size: 1rem;
                    font-weight: 400;
                }
            }
            /* 50 126 248 */
        </style>
    </head>
    <body>
        <div class="container">
            <div class="inner-container">
                <form action="{{ route('rPassword') }}" method="post">
                    <div style="display: flex; flex-direction: column; gap: 1em">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                        <div class="input-container">
                            <label for="new-password">New Password</label>
                            <input
                                type="password" 
                                name="password"
                                placeholder="Enter your new password"
                            />
                        </div>
                        <div class="input-container">
                            <label for="confirm-password"
                                >Confirm Password</label>
                            <input
                                type="password" 
                                name="password_confirmation"
                                placeholder="Re-enter your new password"
                            />
                        </div>
                    </div>
                    <button type="submit" name="submitBtn">Change Password</button>
                </form>
            </div>
        </div>
        <script src="" async defer>
        </script>
    </body>
</html>
