<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
</head>
<style>
/* Css for button */
.btn {
  margin: 10px;
  width: 140px;
  text-decoration: none;
  height: 18px;
  padding: 15px 30px;
  margin-left: 50px;
  text-align: center;
  text-transform: uppercase;
  transition: 0.5s;
  background-size: 200% auto;
  color: white;
  border-radius: 10px;
  display: block;
  border: 0px;
  font-size: 14px;
  /* font-weight: 400; */
  box-shadow: 0px 0px 14px -7px #f09819;
  background-image: linear-gradient(45deg, #FF512F 0%, #F09819  51%, #FF512F  100%);
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

/* Mobile */
/* @media only screen and (min-width: 480px) {
    .btn {margin-left: 5px;}
} */

/* Tablet */
@media only screen and (min-width: 768px) {
    .btn {margin-left: 150px;}
}

/* Desktop */
@media only screen and (min-width: 992px) {
    .btn {margin-left: 250px;}
}

/* Huge */
@media only screen and (min-width: 1280px) {
    .btn {margin-left: 300px;}
}



.btn:hover {
  background-position: right center;
  color: #fff;
  text-decoration: none;
}

.btn:active {
  transform: scale(0.95);
}

/* Css for mail content */
.greetings {
    color: rgb(76, 99, 123);
}
.desc {
    color: rgb(28, 52, 79);
}
.footnote {
    color: rgb(92, 112, 132);
}
</style>
<body>
    <h3 class="greetings">Dear {{ $name }},</h3>
    <br>
    <p class="desc">We have received a request to change the password for your saralprint account.</p>
    <p class="desc">{{ $data }}</p>
    <br>
    <a class="btn" role="button" href="{{ route('passwordResetForm',[ $token]) }}"><div>Reset Password</div></a>
    <br>
    <p class="footnote"><i>If you did not initiate this request, please contact us immediately at <a mailto:support@saralprint.com>support@saralprint.com</a></i></p>
<br>
<p class="footnote">Thank you,</p>
<p class="footnote">saralprint &copy; 2014 - 2022</p>
</body>
</html>