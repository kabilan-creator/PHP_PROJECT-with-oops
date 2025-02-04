<?php
require "../config/database.php";
require '../config/helper.php';




$token=$_GET["token"];


// if (isset($_GET['token'])) {
//     echo "Token: " . htmlspecialchars($_GET['token']);
// } else {
//     echo "Token not provided.";
// }



$sql = "SELECT*FROM admin
        WHERE account_activation=?";

$stmt = $db->prepare($sql);

if ($stmt === false) {
    // Display the error if statement preparation fails
    die("SQL error: " . $db->error);
}
$stmt->bind_param("s", $token);

$stmt->execute();

$result=$stmt->get_result();

$user = $result->fetch_assoc();

if ($user== null) {
    die("token not found");
}
$sql = "UPDATE admin
        SET account_activation = NULL
        WHERE AID=?";
$stmt = $db->prepare($sql);

mysqli_stmt_bind_param($stmt,"s",$user["AID"]);

$stmt->execute();

?>
<html lang="en"><head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>account activation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.min.css') ?>'>
<script defer="" referrerpolicy="origin" src="/cdn-cgi/zaraz/s.js?z=JTdCJTIyZXhlY3V0ZWQlMjIlM0ElNUIlNUQlMkMlMjJ0JTIyJTNBJTIyQWRtaW5MVEUlMjAzJTIwJTdDJTIwUmVjb3ZlciUyMFBhc3N3b3JkJTIyJTJDJTIyeCUyMiUzQTAuNjA3MTE3MTI5ODkwNjI3NSUyQyUyMnclMjIlM0ExNTM2JTJDJTIyaCUyMiUzQTg2NCUyQyUyMmolMjIlM0E3MzAlMkMlMjJlJTIyJTNBMTUzNiUyQyUyMmwlMjIlM0ElMjJodHRwcyUzQSUyRiUyRmFkbWlubHRlLmlvJTJGdGhlbWVzJTJGdjMlMkZwYWdlcyUyRmV4YW1wbGVzJTJGcmVjb3Zlci1wYXNzd29yZC5odG1sJTIyJTJDJTIyciUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGYWRtaW5sdGUuaW8lMkZ0aGVtZXMlMkZ2MyUyRnBhZ2VzJTJGZXhhbXBsZXMlMkY0MDQuaHRtbCUyMiUyQyUyMmslMjIlM0EyNCUyQyUyMm4lMjIlM0ElMjJVVEYtOCUyMiUyQyUyMm8lMjIlM0EtMzMwJTJDJTIycSUyMiUzQSU1QiU1RCU3RA=="></script><script data-cfasync="false" nonce="c7ae629c-92a6-40f1-9843-8cb889bd5eaf">try{(function(w,d){!function(a,b,c,d){if(a.zaraz)console.error("zaraz is loaded twice");else{a[c]=a[c]||{};a[c].executed=[];a.zaraz={deferred:[],listeners:[]};a.zaraz._v="5848";a.zaraz._n="c7ae629c-92a6-40f1-9843-8cb889bd5eaf";a.zaraz.q=[];a.zaraz._f=function(e){return async function(){var f=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:f})}};for(const g of["track","set","debug"])a.zaraz[g]=a.zaraz._f(g);a.zaraz.init=()=>{var h=b.getElementsByTagName(d)[0],i=b.createElement(d),j=b.getElementsByTagName("title")[0];j&&(a[c].t=b.getElementsByTagName("title")[0].text);a[c].x=Math.random();a[c].w=a.screen.width;a[c].h=a.screen.height;a[c].j=a.innerHeight;a[c].e=a.innerWidth;a[c].l=a.location.href;a[c].r=b.referrer;a[c].k=a.screen.colorDepth;a[c].n=b.characterSet;a[c].o=(new Date).getTimezoneOffset();if(a.dataLayer)for(const k of Object.entries(Object.entries(dataLayer).reduce(((l,m)=>({...l[1],...m[1]})),{})))zaraz.set(k[0],k[1],{scope:"page"});a[c].q=[];for(;a.zaraz.q.length;){const n=a.zaraz.q.shift();a[c].q.push(n)}i.defer=!0;for(const o of[localStorage,sessionStorage])Object.keys(o||{}).filter((q=>q.startsWith("_zaraz_"))).forEach((p=>{try{a[c]["z_"+p.slice(7)]=JSON.parse(o.getItem(p))}catch{a[c]["z_"+p.slice(7)]=o.getItem(p)}}));i.referrerPolicy="origin";i.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a[c])));h.parentNode.insertBefore(i,h)};["complete","interactive"].includes(b.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async bs=>new Promise((bt=>{if(bs){bs.e&&bs.e.forEach((bu=>{try{const bv=d.querySelector("script[nonce]"),bw=bv?.nonce||bv?.getAttribute("nonce"),bx=d.createElement("script");bw&&(bx.nonce=bw);bx.innerHTML=bu;bx.onload=()=>{d.head.removeChild(bx)};d.head.appendChild(bx)}catch(by){console.error(`Error executing script: ${bu}\n`,by)}}));Promise.allSettled((bs.f||[]).map((bz=>fetch(bz[0],bz[1]))))}bt()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script><script>(function(w,d){})(window,document)</script><script>(function(w,d){;w.zarazData.executed.push("Pageview");})(window,document)</script><script>(function(w,d){})(window,document)</script></head>
<body class="login-page" data-new-gr-c-s-check-loaded="14.1216.0" data-gr-ext-installed="" style="min-height: 386.4px;">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Account Activation</p>

      <p>Account activation successfully.you can now.</p>

       

      <p class="mt-3 mb-1">
        <a href='<?php echo url('/views/auth/login.php') ?>'>Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js?v=3.2.0"></script>


</body><grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration></html>