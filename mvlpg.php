<title>Many-Valued Logic PlayGround</title>
<?php if ($_SERVER['SERVER_NAME'] == "bexandre.alwaysdata.net") {
?>
<meta http-equiv="refresh" content="0; https://alexandrae.fr<?=$_SERVER['REQUEST_URI'];?>">
<?php } ?>
<meta name="description" content="An online tool to quickly investigate valid inferences in many-valued logics.">
<meta name="author" content="alexandræ">
<script id='MathJax-script' async src='https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js'></script>
<?php

foreach ($_GET as $name => $value) {
	$_GET[$name] = str_replace(['"',"'",'<','>'],['&quot;','’','&lt;','&gt;'],$value);
	if (in_array($name, ["n","inf_np","inf_nc","inf_nv"]))
		$_GET[$name] = intval($_GET[$name]);
}

$min_n = 1;
$max_n = 14;

$nope = False;

if (isset($_GET["description"])) {
	$description = $_GET["description"];
} else {
	$description = "N/A";
}

if (isset($_GET["inf_c0"])) {
	if (intval($_GET["inf_np"]) < 0 or intval($_GET["inf_np"]) > 5
	or intval($_GET["inf_nc"]) < 1 or intval($_GET["inf_nc"]) > 5
	or intval($_GET["inf_nv"]) < 1 or intval($_GET["inf_nv"]) > 5) {
		$nope = True;
		print "nice try.<meta http-equiv='refresh' content='1; ?'>";
	}
	for ($i = 0; $i < $_GET["inf_np"]; $i++)
	if ("" != str_replace(["and(","or(","implies(","not(",",","A","B","C","D","E",")"," "],[""],$_GET["inf_p$i"])) {
		$nope = True;
		print "nice try.<meta http-equiv='refresh' content='1; ?'>";
	}
	for ($i = 0; $i < $_GET["inf_nc"]; $i++)
	if ("" != str_replace(["and(","or(","implies(","not(",",","A","B","C","D","E",")"," "],[""],$_GET["inf_c$i"])) {
		$nope = True;
		print "nice try.<meta http-equiv='refresh' content='1; ?'>";
	}
}

if (isset($_GET["n"])) {
	if ($_GET["n"] < $min_n or $_GET["n"] > $max_n) {
		$nope = True;
		print "nice try.<meta http-equiv='refresh' content='1; ?'>";
	}
}
if (isset($_GET["v0"])) {
	$memory = [];
	for ($i = 0; $i < intval($_GET["n"]); $i++) {
		if (in_array($_GET["v$i"], $memory)) {
			$nope = True;
			print "nice try.<meta http-equiv='refresh' content='1; ?'>";
		}
		$memory[count($memory)] = $_GET["v$i"];
	}
}
if (!$nope) {
?>
<style>
a {
	color: rgb(100,176,255)
}

body {
	background-color: black;
	color: white;
}

input, textarea, select {
	background-color: rgb(50,50,50);
	color: white;
}

table[b] { 
	border: solid 1px grey;
}

td[b] {
	padding: 5px;
	text-align: center;
	border: solid 1px grey;
}

span.wff {
	background-color: rgb(120, 0, 64);
}

span.wff i, i.wff {
	background-color: rgb(240, 0, 255);
	border: solid 1px rgb(178, 0, 255);
}

nav{
    width: 100%;
    top: 0px;
	cursor: pointer;
}

nav ul {
	padding: 0px;
    list-style-type: none;
}

nav a {
	cursor: grab;
	text-decoration: none;
	color: rgb(255,200,200);
}

nav a:hover {
	text-decoration: underline;
	color: orange;
}

nav ul li{
    float: left;
    width: 100%;
    text-align: center;
    position: relative;
}

nav ul::after{
    content: "";
    display: table;
    clear: both;
}

.deroulant {
	padding: 0px;
    background-color: rgb(0, 30, 63);
    width: 100%;
}

.sous{
    display: none;
	background-color: rgb(0, 48, 90);
    position: absolute;
    width: 100%;
}

nav > ul li:hover .sous{
    display: block;
}

.sous li{
    float: none;
    width: 50%;
    text-align: left;
}
</style>
<?php
if (isset($_GET["n"])) {
	$n = $_GET["n"];
	if (isset($_GET["v0"])) {
		$designated = [];
		for ($i = 0; $i < $n; $i++) {
			$v[$i] = $_GET["v$i"];
			if ($_GET["d$i"] == "y") {
				$designated[count($designated)] = $_GET["v$i"];
			}
		}
		if (isset($_GET["N0"])) {
			for ($i = 0; $i < $n; $i++) {
				$N[$v[$i]] = $_GET["N$i"];
				for ($j = 0; $j < $n; $j++) {
					$K[$v[$i]][$v[$j]] = $_GET["K$i;$j"];
					$A[$v[$i]][$v[$j]] = $_GET["A$i;$j"];
					$C[$v[$i]][$v[$j]] = $_GET["C$i;$j"];
				}
			}
		} else {
			for ($i = 0; $i < $n; $i++) {
				$N[$v[$i]] = $v[$n-1-$i];
				for ($j = 0; $j < $n; $j++) {
					$K[$v[$i]][$v[$j]] = $v[max($i,$j)];
					$A[$v[$i]][$v[$j]] = $v[min($i,$j)];
					if ($i >= $j) {
						$C[$v[$i]][$v[$j]] = $v[0];
					} else {
						$C[$v[$i]][$v[$j]] = $v[$j];
					}
				}
			}
		}
	} else {
		$designated = [$n];
		for ($i = 0; $i < $n; $i++) {
			$v[$i] = $n-$i;
		}
	}
} else {
	$n = 2;
	$v = ["true", "false"];
	$designated = ["true"];
}
?>
<center>
<h1><a href="?">Many-Valued Logic PlayGround</a></h1>
<i>See also&nbsp;: <a href="https://users.cecs.anu.edu.au/~jks/magic.html" target="_blank">MaGIC</a> (Slaney)&nbsp;; <a href="https://github.com/Usernamealexandraeisnotavailable/logics/blob/main/mvlpg.php" target="_blank">Source code</a> (please don't judge... lol)</i>
<nav>
  <ul>
    <li class="deroulant"><b><i>Examples</i> &#9660;</b></a>
	  <center>
      <ul class="sous">
		<li><a href="?n=2&v0=True&d0=y&v1=False&d1=n&description=Standard+logic+(Frege%2C+1879+%3B+Russell+%26+Whitehead%2C+1910)">Frege-Russell logic</a> (<a href="https://gallica.bnf.fr/ark:/12148/bpt6k65658c" style="color: rgb(220,255,255)" target="_blank">Frege, 1879</a>&nbsp;; <a href="https://en.wikisource.org/wiki/Russell_%26_Whitehead%27s_Principia_Mathematica" style="color: rgb(220,255,255)" target="_blank">Russell &amp; Whitehead, 1910</a>),<br>&nbsp;&nbsp;&nbsp;&nbsp;a.k.a. "classical" logic, "standard" logic...
		<li><a href="?n=1&v0=both&d0=y&description=Trivial+logic+only+has+one+truth+value,+which+happens+to+be+designated.">Trivial</a>
		<li><a href="?n=1&v0=neither&d0=n&description=Nihilism%2C+at+least+in+this+context%2C+is+what+happens+when+there+is+no+designated+value.+Note+that+there+could+also+be+a+stronger+anti-inferential+nihilism+that+admits+no+rule.">Nihilistic</a>
		<li><a href="?n=3&v0=1&d0=y&v1=1%2F2&d1=n&v2=0&d2=n&N0=0&N1=0&N2=1&K0%3B0=1&K0%3B1=1%2F2&K0%3B2=0&K1%3B0=1%2F2&K1%3B1=1%2F2&K1%3B2=0&K2%3B0=0&K2%3B1=0&K2%3B2=0&A0%3B0=1&A0%3B1=1&A0%3B2=1&A1%3B0=1&A1%3B1=1%2F2&A1%3B2=1%2F2&A2%3B0=1&A2%3B1=1%2F2&A2%3B2=0&C0%3B0=1&C0%3B1=1%2F2&C0%3B2=0&C1%3B0=1&C1%3B1=1&C1%3B2=0&C2%3B0=1&C2%3B1=1&C2%3B2=1&description=This+is+an+example+of+a+3-valued+Heyting+algebra.">3-valued Heyting algebra</a>
		<li><a href="?n=4&v0=11&d0=y&v1=10&d1=n&v2=01&d2=n&v3=00&d3=n&N0=00&N1=01&N2=10&N3=11&K0%3B0=11&K0%3B1=10&K0%3B2=01&K0%3B3=00&K1%3B0=10&K1%3B1=10&K1%3B2=00&K1%3B3=00&K2%3B0=01&K2%3B1=00&K2%3B2=01&K2%3B3=00&K3%3B0=00&K3%3B1=00&K3%3B2=00&K3%3B3=00&A0%3B0=11&A0%3B1=11&A0%3B2=11&A0%3B3=11&A1%3B0=11&A1%3B1=10&A1%3B2=11&A1%3B3=10&A2%3B0=11&A2%3B1=11&A2%3B2=01&A2%3B3=01&A3%3B0=11&A3%3B1=10&A3%3B2=01&A3%3B3=00&C0%3B0=11&C0%3B1=10&C0%3B2=01&C0%3B3=00&C1%3B0=11&C1%3B1=11&C1%3B2=01&C1%3B3=01&C2%3B0=11&C2%3B1=10&C2%3B2=11&C2%3B3=10&C3%3B0=11&C3%3B1=11&C3%3B2=11&C3%3B3=11&description=This+is+an+example+of+a+4-valued+Boolean+algebra.">4-valued Boolean algebra</a>
		<li><a href="?n=3&v0=F&d0=n&v1=U&d1=n&v2=T&d2=y&N0=T&N1=U&N2=F&K0%3B0=F&K0%3B1=F&K0%3B2=F&K1%3B0=F&K1%3B1=U&K1%3B2=U&K2%3B0=F&K2%3B1=U&K2%3B2=T&A0%3B0=F&A0%3B1=U&A0%3B2=T&A1%3B0=U&A1%3B1=U&A1%3B2=T&A2%3B0=T&A2%3B1=T&A2%3B2=T&C0%3B0=T&C0%3B1=T&C0%3B2=T&C1%3B0=U&C1%3B1=U&C1%3B2=T&C2%3B0=F&C2%3B1=U&C2%3B2=T&description=Strong+Kleene+logic+of+indeterminacy+%28Kleene%2C+1938%29">Strong Kleene</a> (<a href="https://doi.org/10.2307/2267778" style="color: rgb(220,255,255)" target="_blank">Kleene, 1938</a>)
		<li><a href="?n=4&v0=1&d0=y&v1=2&d1=y&v2=3&d2=n&v3=4&d3=n&N0=4&N1=3&N2=2&N3=1&K0%3B0=1&K0%3B1=2&K0%3B2=3&K0%3B3=4&K1%3B0=1&K1%3B1=2&K1%3B2=4&K1%3B3=3&K2%3B0=3&K2%3B1=3&K2%3B2=3&K2%3B3=4&K3%3B0=4&K3%3B1=3&K3%3B2=4&K3%3B3=3&A0%3B0=2&A0%3B1=1&A0%3B2=2&A0%3B3=1&A1%3B0=1&A1%3B1=2&A1%3B2=2&A1%3B3=2&A2%3B0=2&A2%3B1=1&A2%3B2=3&A2%3B3=4&A3%3B0=1&A3%3B1=2&A3%3B2=3&A3%3B3=4&C0%3B0=1&C0%3B1=4&C0%3B2=3&C0%3B3=4&C1%3B0=4&C1%3B1=1&C1%3B2=4&C1%3B3=3&C2%3B0=1&C2%3B1=4&C2%3B2=1&C2%3B3=4&C3%3B0=4&C3%3B1=1&C3%3B2=4&C3%3B3=1&description=CC1+%28Angell%2C+1962+%3B+McCall%2C+1966%29"><b>CC1</b></a> (<a href="https://doi.org/10.2307/2964651" style="color: rgb(220,255,255)" target="_blank">Angell, 1962</a>&nbsp;; <a href="https://doi.org/10.2307/2270458" style="color: rgb(220,255,255)" target="_blank">McCall, 1966</a>)
		<li><a href="?n=4&v0=true&d0=y&v1=both&d1=y&v2=neither&d2=n&v3=false&d3=n&description=FDE+(Dunn%2C+1976+%3B+Belnap%2C+1977)%0D%0A%0D%0AFDE+has+no+native+⊃%2C+I+used+a+table+of+a+connexive-ish+expansion+of+FDE+with+the+variable-sharing+property+shown+in+a+talk+by+Robbles+(2024)+%3A+https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DHm1JAKULW1c&N0=false&N1=both&N2=neither&N3=true&K0%3B0=true&K0%3B1=both&K0%3B2=neither&K0%3B3=false&K1%3B0=both&K1%3B1=both&K1%3B2=false&K1%3B3=false&K2%3B0=neither&K2%3B1=false&K2%3B2=neither&K2%3B3=false&K3%3B0=false&K3%3B1=false&K3%3B2=false&K3%3B3=false&A0%3B0=true&A0%3B1=true&A0%3B2=true&A0%3B3=true&A1%3B0=true&A1%3B1=both&A1%3B2=true&A1%3B3=both&A2%3B0=true&A2%3B1=true&A2%3B2=neither&A2%3B3=neither&A3%3B0=true&A3%3B1=both&A3%3B2=neither&A3%3B3=false&C0%3B0=true&C0%3B1=both&C0%3B2=neither&C0%3B3=false&C1%3B0=neither&C1%3B1=both&C1%3B2=neither&C1%3B3=false&C2%3B0=neither&C2%3B1=neither&C2%3B2=both&C2%3B3=neither&C3%3B0=false&C3%3B1=neither&C3%3B2=neither&C3%3B3=true"><b>FDE</b></a> (<a href="https://doi.org/10.1007/978-3-030-31136-0_3" style="color: rgb(220,255,255)" target="_blank">Dunn, 1976</a>&nbsp;; <a href="https://doi.org/10.1007/978-94-010-1161-7_2" style="color: rgb(220,255,255)" target="_blank">Belnap, 1977</a>),<br>&nbsp;&nbsp;&nbsp;&nbsp;a.k.a. Belnap&ndash;Dunn logic
		<li><a href="?n=3&v0=F&d0=n&v1=B&d1=y&v2=T&d2=y&N0=T&N1=B&N2=F&K0%3B0=F&K0%3B1=F&K0%3B2=F&K1%3B0=F&K1%3B1=B&K1%3B2=B&K2%3B0=F&K2%3B1=B&K2%3B2=T&A0%3B0=F&A0%3B1=B&A0%3B2=T&A1%3B0=B&A1%3B1=B&A1%3B2=T&A2%3B0=T&A2%3B1=T&A2%3B2=T&C0%3B0=T&C0%3B1=T&C0%3B2=T&C1%3B0=B&C1%3B1=B&C1%3B2=T&C2%3B0=F&C2%3B1=B&C2%3B2=T&description=LP+%28Priest%2C+1979%29%0D%0A%0D%0ALP+stands+for+logic+of+paradox"><b>LP</b></a> (<a href="https://doi.org/10.1007/BF00258428" style="color: rgb(220,255,255)" target="_blank">Priest, 1979</a>)
		<li><a href="?n=3&v0=T&d0=y&v1=*&d1=y&v2=F&d2=n&N0=F&N1=*&N2=T&K0%3B0=T&K0%3B1=*&K0%3B2=F&K1%3B0=*&K1%3B1=*&K1%3B2=F&K2%3B0=F&K2%3B1=F&K2%3B2=F&A0%3B0=T&A0%3B1=T&A0%3B2=T&A1%3B0=T&A1%3B1=*&A1%3B2=*&A2%3B0=T&A2%3B1=*&A2%3B2=F&C0%3B0=*&C0%3B1=F&C0%3B2=F&C1%3B0=*&C1%3B1=*&C1%3B2=F&C2%3B0=*&C2%3B1=*&C2%3B2=*&description=M3V+%28Mortensen%2C+1984+%3B+McCall%2C+2012%29%0D%0A%0D%0AMortensen’s+3-valued+logic."><b>M3V</b></a> (<a href="https://doi.org/10.1007/BF00935744" style="color: rgb(220,255,255)" target="_blank">Mortensen, 1984</a>),<br>&nbsp;&nbsp;&nbsp;&nbsp;named as such from <a href="https://doi.org/10.1016/B978-0-444-52937-4.50008-3" style="color: rgb(220,255,255)" target="_blank">McCall, 2012</a>
		<li>Example of a 3-valued <a href="?n=3&v0=3&d0=y&v1=2&d1=n&v2=1&d2=n&description=This+is+an+example+of+a+3-valued+model+for+a+%7B%E2%86%92%2C+%7E%7D-fragment+of+abelian+logic%2C+as+there+is+no+%28non-trivial%29+finite+abelian+%26ell%3B-groups+to+model+genuine+abelian+logics+%E2%80%94+but+if+all+we+care+about+is+satisfying+the+relativity+axiom+%28cf.+Meyer+%26+Slaney%29%2C+one+can+argue+we+do+not+need+the+lattice+part%2C+and+only+focus+on+%E2%86%92+and+%7E%2C+which+can+be+described+using+abelian+groups+%28some+of+which+are+indeed+finite%29.+Thus%2C+the+conjunction+and+disjunction+below+operations%2C+along+with+the+theorems+that+use+them%2C+are+mere+noise.&N0=1&N1=1&N2=3&K0%3B0=3&K0%3B1=2&K0%3B2=1&K1%3B0=2&K1%3B1=2&K1%3B2=1&K2%3B0=1&K2%3B1=1&K2%3B2=1&A0%3B0=3&A0%3B1=3&A0%3B2=3&A1%3B0=3&A1%3B1=2&A1%3B2=2&A2%3B0=3&A2%3B1=2&A2%3B2=1&C0%3B0=3&C0%3B1=2&C0%3B2=1&C1%3B0=1&C1%3B1=3&C1%3B2=2&C2%3B0=2&C2%3B1=1&C2%3B2=3">{&rarr;, ~}-fragment of abelian logic</a> (<a href="https://doi.org/10.2307/j.ctv2x8v8c7.12" style="color: rgb(220,255,255)" target="_blank">Meyer &amp; Slaney, 1989</a>)
		<li><a href="?n=4&v0=T&d0=y&v1=B&d1=y&v2=N&d2=n&v3=F&d3=n&description=MC+(Wansing%2C+2005)+as+dBD+(Omori%2C+2016).%0D%0A%0D%0AMC+stands+for+Material+Connexive.&N0=F&N1=B&N2=N&N3=T&K0%3B0=T&K0%3B1=B&K0%3B2=N&K0%3B3=F&K1%3B0=B&K1%3B1=B&K1%3B2=F&K1%3B3=F&K2%3B0=N&K2%3B1=F&K2%3B2=N&K2%3B3=F&K3%3B0=F&K3%3B1=F&K3%3B2=F&K3%3B3=F&A0%3B0=T&A0%3B1=T&A0%3B2=T&A0%3B3=T&A1%3B0=T&A1%3B1=B&A1%3B2=T&A1%3B3=B&A2%3B0=T&A2%3B1=T&A2%3B2=N&A2%3B3=N&A3%3B0=T&A3%3B1=B&A3%3B2=N&A3%3B3=F&C0%3B0=T&C0%3B1=B&C0%3B2=N&C0%3B3=F&C1%3B0=T&C1%3B1=B&C1%3B2=N&C1%3B3=F&C2%3B0=B&C2%3B1=B&C2%3B2=B&C2%3B3=B&C3%3B0=B&C3%3B1=B&C3%3B2=B&C3%3B3=B"><b>MC</b></a> (<a href="http://www.aiml.net/volumes/volume5/" style="color: rgb(220,255,255)" target="_blank">Wansing, 2005</a>) as <b>dBD</b> (<a href="https://doi.org/10.1007/978-3-319-40220-8_8" style="color: rgb(220,255,255)" target="_blank">Omori, 2016</a>)
		<li><a href="?n=3&v0=T&d0=y&v1=F&d1=n&v2=--&d2=y&description=CN+%28Cantwell%2C+2008%29%2C+which+is+essentially%E2%80%89MC+but+with+the+law+of+excluded+middle.%0D%0A%0D%0ACN+stands+for+Conditional+Negation.&N0=F&N1=T&N2=--&K0%3B0=T&K0%3B1=F&K0%3B2=--&K1%3B0=F&K1%3B1=F&K1%3B2=F&K2%3B0=--&K2%3B1=F&K2%3B2=--&A0%3B0=T&A0%3B1=T&A0%3B2=T&A1%3B0=T&A1%3B1=F&A1%3B2=--&A2%3B0=T&A2%3B1=--&A2%3B2=--&C0%3B0=T&C0%3B1=F&C0%3B2=--&C1%3B0=--&C1%3B1=--&C1%3B2=--&C2%3B0=T&C2%3B1=F&C2%3B2=--"><b>CN</b></a> (<a href="https://doi.org/10.1215/00294527-2008-010" style="color: rgb(220,255,255)" target="_blank">Cantwell, 2008</a>)
		<li><a href="?n=3&v0=1&d0=y&v1=2&d1=n&v2=3&d2=n&N0=3&N1=1&N2=1&K0%3B0=1&K0%3B1=2&K0%3B2=3&K1%3B0=2&K1%3B1=2&K1%3B2=3&K2%3B0=3&K2%3B1=3&K2%3B2=3&A0%3B0=1&A0%3B1=1&A0%3B2=1&A1%3B0=1&A1%3B1=2&A1%3B2=2&A2%3B0=1&A2%3B1=2&A2%3B2=3&C0%3B0=1&C0%3B1=2&C0%3B2=3&C1%3B0=2&C1%3B1=2&C1%3B2=2&C2%3B0=2&C2%3B1=2&C2%3B2=2&description=MRS%5EP+%28Estrada-González%2C+2008%29"><b>MRS<sup><i>P</i></sup></b></a> (<a href="https://doi.org/10.1007/s11787-007-0024-9" style="color: rgb(220,255,255)" target="_blank">Estrada-González, 2008</a>)
		<li><a href="?n=3&v0=Ω&d0=y&v1=∅&d1=n&v2=N%2FA&d2=n&description=-+This+logic+is+inspired+by+conditional+probability%0D%0A-+Ω+is+a+certain+event%2C+∅+is+an+impossible+event%0D%0A-+By+definition%2C+P%28B%7CA%29+%3D+P%28B%26A%29%2FP%28A%29%2C+so+if+A%3D∅%2C+that+yields+a+ZeroDivisionError%2C+hence+the+N%2FA+value&N0=∅&N1=Ω&N2=N%2FA&K0%3B0=Ω&K0%3B1=∅&K0%3B2=N%2FA&K1%3B0=∅&K1%3B1=∅&K1%3B2=N%2FA&K2%3B0=N%2FA&K2%3B1=N%2FA&K2%3B2=N%2FA&A0%3B0=Ω&A0%3B1=Ω&A0%3B2=N%2FA&A1%3B0=Ω&A1%3B1=∅&A1%3B2=N%2FA&A2%3B0=N%2FA&A2%3B1=N%2FA&A2%3B2=N%2FA&C0%3B0=Ω&C0%3B1=∅&C0%3B2=N%2FA&C1%3B0=N%2FA&C1%3B1=N%2FA&C1%3B2=N%2FA&C2%3B0=N%2FA&C2%3B1=N%2FA&C2%3B2=N%2FA">&Omega;&ndash;&varnothing; conditional probabilities</a>
	  </ul>
	</li>
  </ul>
</nav>
<table>
<tr><td>
<ul>
<tr><td><form method='get'><b>Number of values :</b> <input type='number' value='<?=$n;?>' name='n' min='<?=$min_n;?>' max='<?=$max_n;?>'> <input type='submit' value='submit'></form>
<tr><td><form method='get'><input type="hidden" name="n" value="<?=$n;?>"><b>Values :</b> <ul><?php
for ($i = 0; $i < $n; $i++) {
	if (in_array($v[$i], $designated)) {
		print "\n	<li><input type='text' value='".$v[$i]."' name='v$i'><br>\n	designated ? <input type='radio' name='d$i' value='y' checked> yes / <input type='radio' name='d$i' value='n'> no";
	} else {
		print "\n	<li><input type='text' value='".$v[$i]."' name='v$i'><br>\n	designated ? <input type='radio' name='d$i' value='y'> yes / <input type='radio' name='d$i' value='n' checked> no";
	}
}
?></ul>
<?php if (!isset($_GET["v0"])) { ?>
<b>Description :</b><br>
<textarea name="description" style="width: 100%; height: 100px; font-family: Times New Roman"><?=$description;?></textarea><br>
<?php } ?>
<input type='submit' value='submit'></form>
</table>
<?php if (isset($_GET["v0"])) { ?>
<form method="get">
<input type="hidden" name="n" value="<?=$n;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	print "<input type='hidden' name='v$i' value='".$_GET["v$i"]."'>\n";
	print "<input type='hidden' name='d$i' value='".$_GET["d$i"]."'>\n";
}
?>
<table>
<tr><td>
<b>Description :</b><br>
<textarea name="description" style="width: 100%; height: 100px; font-family: Times New Roman"><?=$description;?></textarea><br>
<tr><td><b>Negation :
		<table b cellspacing="0"><tr><td b><i>A</i><td b>&sim;<i>A</i><?php
			for ($i = 0; $i < $n; $i++) {
				print "\n		<tr><td b>".$v[$i]."<td><center><select name='N$i'>\n";
				for ($j = 0; $j < $n; $j++) {
					if ($v[$j] == $N[$v[$i]]) {
						print "			<option value='".$v[$j]."' selected>".$v[$j]."\n";
					} else {
						print "			<option value='".$v[$j]."'>".$v[$j]."\n";
					}
				}
			}
		?>
		</table>
<tr><td><b>Conjunction :
		<table cellspacing="0" b><tr><td b><i>A</i>&nbsp;&amp;&nbsp;<i>B</i><?php
			for ($i = 0; $i < $n; $i++) {
				print "<td b><i>B</i>&nbsp;=&nbsp;".$v[$i];
			}
			for ($i1 = 0; $i1 < $n; $i1++) {
				print "\n<tr><td b><i>A</i>&nbsp;=&nbsp;".$v[$i1];
				for ($i2 = 0; $i2 < $n; $i2++) {
					print "<td><center><select name='K".strval($i1).";".strval($i2)."'>";
					for ($j = 0; $j < $n; $j++) {
						if ($v[$j] == $K[$v[$i1]][$v[$i2]]) {
							print "			<option value='".$v[$j]."' selected>".$v[$j]."\n";
						} else {
							print "			<option value='".$v[$j]."'>".$v[$j]."\n";
						}
					}
				}
			}
		?>
		</table>
<tr><td><b>Disjunction :
		<table cellspacing="0" b><tr><td b><i>A</i>&nbsp;&or;&nbsp;<i>B</i><?php
			for ($i = 0; $i < $n; $i++) {
				print "<td b><i>B</i>&nbsp;=&nbsp;".$v[$i];
			}
			for ($i1 = 0; $i1 < $n; $i1++) {
				print "\n<tr><td b><i>A</i>&nbsp;=&nbsp;".$v[$i1];
				for ($i2 = 0; $i2 < $n; $i2++) {
					print "<td><center><select name='A".strval($i1).";".strval($i2)."'>";
					for ($j = 0; $j < $n; $j++) {
						if ($v[$j] == $A[$v[$i1]][$v[$i2]]) {
							print "			<option value='".$v[$j]."' selected>".$v[$j]."\n";
						} else {
							print "			<option value='".$v[$j]."'>".$v[$j]."\n";
						}
					}
				}
			}
		?>
		</table>
<tr><td><b>Implication :
		<table cellspacing="0" b><tr><td b><i>A</i>&nbsp;&rarr;&nbsp;<i>B</i><?php
			for ($i = 0; $i < $n; $i++) {
				print "<td b><i>B</i>&nbsp;=&nbsp;".$v[$i];
			}
			for ($i1 = 0; $i1 < $n; $i1++) {
				print "\n<tr><td b><i>A</i>&nbsp;=&nbsp;".$v[$i1];
				for ($i2 = 0; $i2 < $n; $i2++) {
					print "<td><center><select name='C".strval($i1).";".strval($i2)."'>";
					for ($j = 0; $j < $n; $j++) {
						if ($v[$j] == $C[$v[$i1]][$v[$i2]]) {
							print "			<option value='".$v[$j]."' selected>".$v[$j]."\n";
						} else {
							print "			<option value='".$v[$j]."'>".$v[$j]."\n";
						}
					}
				}
			}
		?>
		</table>
<tr><td><input type='submit' value='submit'>
</table>
</form>
<hr>
<table cellspacing="20px">
<tr><td colspan='2' align='center'>



<?php
if (isset($_GET["inf_nv"])) {
?>
<form method="get">
<input type="hidden" name="n" value="<?=$n;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	print "<input type='hidden' name='v$i' value='".$_GET["v$i"]."'>\n";
	print "<input type='hidden' name='d$i' value='".$_GET["d$i"]."'>\n";
}
?>
<input type="hidden" name="description" value="<?=$description;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	for ($j = 0; $j < $n; $j++) {
		print "<input type='hidden' name='N$i' value='".$_GET["N$i"]."'>\n";
		print "<input type='hidden' name='C$i;$j' value='".$_GET["C$i;$j"]."'>\n";
		print "<input type='hidden' name='K$i;$j' value='".$_GET["K$i;$j"]."'>\n";
		print "<input type='hidden' name='A$i;$j' value='".$_GET["A$i;$j"]."'>\n";
	}
}
?>
<center><b>Custom inference test</b></center>
<hr>
<b>Number of variables&nbsp;:</b> <input type="number" name="inf_nv" min="1" max="5" value="<?=$_GET["inf_nv"];?>"><br>
<b>Number of premises&nbsp;:</b> <input type="number" name="inf_np" min="0" max="5" value="<?=$_GET["inf_np"];?>"><br>
<b>Number of conclusions&nbsp;:</b> <input type="number" name="inf_nc" min="1" max="5" value="<?=$_GET["inf_nc"];?>"><br>
<input type="submit" value="submit">
</form><br><br>
&bullet; WFF (well-formed formulas) are case-sensitive, but space-insensitive.<br>
&bullet; <span class='wff'>A</span><?php
for ($i = 1; $i < $_GET["inf_nv"]; $i++) {
	print ", <span class='wff'>".['A','B','C','D','E'][$i]."</span>";
}
if ($_GET["inf_nv"] == 1) print " is a wff.";
else print " are wff.";
?><br>
&bullet; If <i class='wff'>&alpha;</i> is a WFF, then so is <span class='wff'>not(<i>&alpha;</i>)</span>.<br>
&bullet; If <i class='wff'>&alpha;</i> and <i class='wff'>&beta;</i> are WFF, then so is <span class='wff'>and(<i>&alpha;</i>, <i>&beta;</i>)</span>.<br>
&bullet; If <i class='wff'>&alpha;</i> and <i class='wff'>&beta;</i> are WFF, then so is <span class='wff'>or(<i>&alpha;</i>, <i>&beta;</i>)</span>.<br>
&bullet; If <i class='wff'>&alpha;</i> and <i class='wff'>&beta;</i> are WFF, then so is <span class='wff'>implies(<i>&alpha;</i>, <i>&beta;</i>)</span>.<br>
<br>

<form method="get">
<input type="hidden" name="n" value="<?=$n;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	print "<input type='hidden' name='v$i' value='".$_GET["v$i"]."'>\n";
	print "<input type='hidden' name='d$i' value='".$_GET["d$i"]."'>\n";
}
?>
<input type="hidden" name="description" value="<?=$description;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	for ($j = 0; $j < $n; $j++) {
		print "<input type='hidden' name='N$i' value='".$N[$v[$i]]."'>\n";
		print "<input type='hidden' name='C$i;$j' value='".$C[$v[$i]][$v[$j]]."'>\n";
		print "<input type='hidden' name='K$i;$j' value='".$K[$v[$i]][$v[$j]]."'>\n";
		print "<input type='hidden' name='A$i;$j' value='".$A[$v[$i]][$v[$j]]."'>\n";
	}
}
?>
<input type="hidden" name="inf_nv" value="<?=$_GET["inf_nv"];?>">
<input type="hidden" name="inf_np" value="<?=$_GET["inf_np"];?>">
<input type="hidden" name="inf_nc" value="<?=$_GET["inf_nc"];?>">
<b>Premise(s)&nbsp;:</b><br>
<?php
for ($i = 0; $i < $_GET["inf_np"]; $i++)
	if (isset($_GET["inf_p$i"]))
	print "<input type='text' name='inf_p$i' value='".$_GET["inf_p$i"]."'><br>\n";
	else
	print "<input type='text' name='inf_p$i'><br>\n";
?><br>
<b>Conclusion(s)&nbsp;:</b><br>
<?php
for ($i = 0; $i < $_GET["inf_nc"]; $i++)
	if (isset($_GET["inf_c$i"]))
	print "<input type='text' name='inf_c$i' value='".$_GET["inf_c$i"]."'><br>\n";
	else
	print "<input type='text' name='inf_c$i'><br>\n";
?>
<input type="submit" value="submit">
</form>

<pre><?php
function latex_implies ($A, $B) {
	return "($A\\to $B)";
}
function latex_and ($A, $B) {
	return "($A\\;\\&\\;$B)";
}
function latex_or ($A, $B) {
	return "($A\\lor $B)";
}
function latex_not ($A) {
	return "{\\sim}$A";
}
function latexer ($A) {
	return str_replace(['lA','lB','lC','lD','lE'],['A','B','C','D','E'],$A);
}
function cond_implies ($A, $B) {
	$new_A = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$A
	);
	$new_B = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$B
	);
	return "\$M[".$new_A."][".$new_B."]";
}
function cond_and ($A, $B) {
	$new_A = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$A
	);
	$new_B = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$B
	);
	return "\$K[".$new_A."][".$new_B."]";
}
function cond_or ($A, $B) {
	$new_A = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$A
	);
	$new_B = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$B
	);
	return "\$L[".$new_A."][".$new_B."]";
}
function cond_not ($A) {
	$new_A = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE'],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4"],
		$A
	);
	return "\$N[".$new_A."]";
}
function conder ($expr) {
	$new_expr = str_replace(
		['lA', 'lB', 'lC', 'lD', 'lE', "\$L", "\$M"],
		["\$v0", "\$v1", "\$v2", "\$v3", "\$v4", "\$A", "\$C"],
		$expr
	);
	return $new_expr;
}
if (isset($_GET["inf_c0"])) {
$prem = [];
$conc = [];
for ($i = 0; $i < $_GET["inf_np"]; $i++) {
	$prem[count($prem)] = $_GET["inf_p$i"];
	for ($j = 0; $j < $_GET["inf_nv"]; $j++) {
		$prem[count($prem)-1] = str_replace (
			['A','B','C','D','E'][$j],
			['"lA"','"lB"','"lC"','"lD"','"lE"'][$j],
			$prem[count($prem)-1]
		);
	}
}
for ($i = 0; $i < $_GET["inf_nc"]; $i++) {
	$conc[count($conc)] = $_GET["inf_c$i"];
	for ($j = 0; $j < $_GET["inf_nv"]; $j++) {
		$conc[count($conc)-1] = str_replace (
			['A','B','C','D','E'][$j],
			['"lA"','"lB"','"lC"','"lD"','"lE"'][$j],
			$conc[count($conc)-1]
		);
	}
}
function latex ($premarr, $concarr) {
	$ret = "\$\$";
	if ($premarr == []) {
		$ret .= "\\overline{";
	} else {
		$ret .= "\\frac{";
		eval("\$ret .= latexer(".str_replace (
			["and","not","implies","or"],
			["latex_and","latex_not","latex_implies","latex_or"],
			$premarr[0]
		).");");
		for ($i = 1; $i < count($premarr); $i++)
		eval("\$ret .= '\\quad ' . latexer(".str_replace (
			["and","not","implies","or"],
			["latex_and","latex_not","latex_implies","latex_or"],
			$premarr[$i]
		).");");
		$ret .= "}{";
	}
	eval("\$ret .= latexer(".str_replace (
		["and","not","implies","or"],
		["latex_and","latex_not","latex_implies","latex_or"],
		$concarr[0]
	).");");
	for ($i = 1; $i < count($concarr); $i++)
	eval("\$ret .= '\\quad ' . latexer(".str_replace (
		["and","not","implies","or"],
		["latex_and","latex_not","latex_implies","latex_or"],
		$concarr[$i]
	).");");
	$ret .= "}\$\$";
	return $ret;
}
function cond ($premarr, $concarr) {
	$ret = "";
	if (count($premarr) >= 1) {
		$ret .= "if (in_array(";
		eval("\$ret .= conder(".str_replace (
			["and","not","implies","or"],
			["cond_and","cond_not","cond_implies","cond_or"],
			$premarr[0]
		).");");
		$ret .= ", \$designated)";
		for ($i = 1; $i < count($premarr); $i++) {
			$ret .= " and in_array(";
			eval("\$ret .= conder(".str_replace (
				["and","not","implies","or"],
				["cond_and","cond_not","cond_implies","cond_or"],
				$premarr[$i]
			).");");
			$ret .= ", \$designated)";
		}
		$ret .= ")\n";
	}
	$ret .= "if (!in_array(";
	eval("\$ret .= conder(".str_replace (
		["and","not","implies","or"],
		["cond_and","cond_not","cond_implies","cond_or"],
		$concarr[0]
	).");");
	$ret .= ", \$designated)";
	for ($i = 1; $i < count($concarr); $i++) {
		$ret .= " and !in_array(";
		eval("\$ret .= conder(".str_replace (
			["and","not","implies","or"],
			["cond_and","cond_not","cond_implies","cond_or"],
			$concarr[$i]
		).");");
		$ret .= ", \$designated)";
	}
	$ret .= ")";
	return $ret;
}
function loops ($num) {
	$ret = "";
	for ($i = 0; $i < $num; $i++) {
		$ret .= "foreach (\$v as \$v$i)\n";
	}
	return $ret;
}
function counter_format ($num) {
	$ret = "\"<i>A</i>&nbsp;=&nbsp;\".\$v0";
	for ($i = 1; $i < $num; $i++) {
		$ret .= ".\",<br>\\n<i>".['A','B','C','D','E'][$i]."</i>&nbsp;=&nbsp;\".\$v$i";
	}
	$ret .= ";";
	return $ret;
}
print "<tr><td valign='top' colspan='2' align='center'><b><i>Custom inference
<tr><td valign='top'>".latex($prem, $conc)."
<td valign='center'>";
eval("\$bool = True;
\$counter = '<hr>\n';
".loops($_GET["inf_nv"]).cond($prem, $conc)." {
	\$bool = False;
	\$counter .= ".counter_format($_GET["inf_nv"])."
	\$counter .= '<hr>\n';
}
print \"<b>Valid&nbsp;?</b> \";
if (\$bool) {
	print \"Yes&nbsp;!\";
} else {
	print \"Nope.<br>\\n<b>Counter-example(s)&nbsp;:</b><br>\\n\$counter\";
}");
}
?></pre>
<?php } else { ?>
<form method="get">
<center><b>Custom inference test</b></center>
<input type="hidden" name="n" value="<?=$n;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	print "<input type='hidden' name='v$i' value='".$_GET["v$i"]."'>\n";
	print "<input type='hidden' name='d$i' value='".$_GET["d$i"]."'>\n";
}
?>
<input type="hidden" name="description" value="<?=$description;?>">
<?php
for ($i = 0; $i < $n; $i++) {
	for ($j = 0; $j < $n; $j++) {
		print "<input type='hidden' name='N$i' value='".$N[$v[$i]]."'>\n";
		print "<input type='hidden' name='C$i;$j' value='".$C[$v[$i]][$v[$j]]."'>\n";
		print "<input type='hidden' name='K$i;$j' value='".$K[$v[$i]][$v[$j]]."'>\n";
		print "<input type='hidden' name='A$i;$j' value='".$A[$v[$i]][$v[$j]]."'>\n";
	}
}
?>
<hr>
<b>Number of variables&nbsp;:</b> <input type="number" name="inf_nv" min="1" max="5"><br>
<b>Number of premises&nbsp;:</b> <input type="number" name="inf_np" min="0" max="5"><br>
<b>Number of conclusions&nbsp;:</b> <input type="number" name="inf_nc" min="1" max="5"><br>
<input type="submit" value="submit">
</form>
<?php } ?>
</table>








<hr>
<table cellspacing="20px">

<tr><td colspan='2' align='center'><b>Inference tests' results</b>
<hr>

<tr><td colspan='2' align='center'><i><b>Double negation

<tr><td valign='top'>$$\frac A{{\sim}{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($v[$i], $designated)) {
			if (!in_array($N[$N[$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{{\sim}{\sim}A}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($N[$N[$v[$i]]], $designated)) {
			if (!in_array($v[$i], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{A\to{\sim}{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$N[$N[$v[$i]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{{\sim}{\sim}A\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$N[$N[$v[$i]]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Self-negation

<tr><td valign='top'>$$\frac A{{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($v[$i], $designated)) {
			if (!in_array($N[$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{{\sim}A}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($N[$v[$i]], $designated)) {
			if (!in_array($v[$i], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{A\to{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$N[$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{{\sim}A\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$N[$v[$i]]][$v[$i]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><hr>

<tr><td colspan='2' align='center'><i><b>Idempotency of &amp;
<tr><td valign='top'>$$\frac A{A\;\&\;A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($v[$i], $designated)) {
			if (!in_array($K[$v[$i]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\frac{A\;\&\;A}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($K[$v[$i]][$v[$i]], $designated)) {
			if (!in_array($v[$i], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{A\to(A\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$K[$v[$i]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{(A\;\&\;A)\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$K[$v[$i]][$v[$i]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Law of noncontradiction
<tr><td valign='top'>$$\overline{{\sim}(A\;\&\;{\sim}A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$K[$v[$i]][$N[$v[$i]]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}({\sim}A\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$K[$N[$v[$i]]][$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>&amp; intro.
<tr><td valign='top'>$$\frac{A\quad B}{A\;\&\;B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$i], $designated) and in_array($v[$j], $designated)) {
				if (!in_array($K[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Simplification, &amp; elim.
<tr><td valign='top'>$$\frac{A\;\&\;B}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($v[$i], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\;\&\;B}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\;\&\;B)\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$K[$v[$i]][$v[$j]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\;\&\;B)\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$K[$v[$i]][$v[$j]]][$v[$j]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Symmetry of &amp;
<tr><td valign='top'>$$\frac{A\;\&\;B}{B\;\&\;A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($K[$v[$j]][$v[$i]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{(A\;\&\;B)\to(B\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$K[$v[$i]][$v[$j]]][$K[$v[$j]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Ex contradictione quodlibet
<tr><td valign='top'>$$\frac{A\quad{\sim}A}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$i], $designated) and in_array($N[$v[$i]], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\;\&\;{\sim}A}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$v[$i]][$N[$v[$i]]], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\frac{{\sim}A\;\&\;A}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$N[$v[$i]]][$v[$i]], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\;\&\;{\sim}A)\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$K[$v[$i]][$N[$v[$i]]]][$v[$j]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{({\sim}A\;\&\;A)\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$K[$N[$v[$i]]][$v[$i]]][$v[$j]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Associativity of &amp;
<tr><td valign='top'>$$\frac{(A\;\&\;B)\;\&\;C}{A\;\&\;(B\;\&\;C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($K[$K[$v[$i]][$v[$j]]][$v[$k]], $designated))
			if (!in_array($K[$v[$i]][$K[$v[$j]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\;\&\;(B\;\&\;C)}{(A\;\&\;B)\;\&\;C}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($K[$v[$i]][$K[$v[$j]][$v[$k]]], $designated))
			if (!in_array($K[$K[$v[$i]][$v[$j]]][$v[$k]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>


<tr><td colspan='2' align='center'><hr>
<tr><td colspan='2' align='center'><i><b>Idempotency of &or;
<tr><td valign='top'>$$\frac A{A\lor A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($v[$i], $designated)) {
			if (!in_array($A[$v[$i]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\lor A}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (in_array($A[$v[$i]][$v[$i]], $designated)) {
			if (!in_array($v[$i], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{A\to(A\lor A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$A[$v[$i]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{(A\lor A)\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$A[$v[$i]][$v[$i]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Law of excluded middle
<tr><td valign='top'>$$\overline{A\lor{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($A[$v[$i]][$N[$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}A\lor A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($A[$N[$v[$i]]][$v[$i]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{A\to(B\lor{\sim}B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++)
		if (!in_array($C[$v[$i]][$A[$v[$j]][$N[$v[$j]]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{A\to({\sim}B\lor B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++)
		if (!in_array($C[$v[$i]][$A[$N[$v[$j]]][$v[$j]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Addition, &or; intro.
<tr><td valign='top'>$$\frac A{A\lor B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$i], $designated)) {
				if (!in_array($A[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac B{A\lor B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$j], $designated)) {
				if (!in_array($A[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{A\to(A\lor B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$v[$i]][$A[$v[$i]][$v[$j]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{B\to(A\lor B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$v[$j]][$A[$v[$i]][$v[$j]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Exclusive &or; intro.
<tr><td valign='top'>$$\frac{A\quad{\sim}B}{A\lor B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$i], $designated) and in_array($N[$v[$j]], $designated)) {
				if (!in_array($A[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}A\quad B}{A\lor B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$v[$i]], $designated) and in_array($v[$j], $designated)) {
				if (!in_array($A[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Affirming a disjunct, exclusive &or; elim.
<tr><td valign='top'>$$\frac{A\lor B\quad A}{{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($v[$i], $designated)) {
				if (!in_array($N[$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor B\quad B}{{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($v[$j], $designated)) {
				if (!in_array($N[$v[$i]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Dual nd &or; elim.
<tr><td valign='top'>$$\frac{A\lor B}{A\quad B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($v[$i], $designated) and !in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Symmetry of &or;
<tr><td valign='top'>$$\frac{A\lor B}{B\lor A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($A[$v[$j]][$v[$i]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{(A\lor B)\to(B\lor A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$A[$v[$i]][$v[$j]]][$A[$v[$j]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Disjunctive syllogism
<tr><td valign='top'>$$\frac{A\lor B\quad{\sim}A}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($N[$v[$i]], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor B\quad{\sim}B}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($N[$v[$j]], $designated)) {
				if (!in_array($v[$i], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Disjunctive syllogism (standard implication form)
<tr><td valign='top'>$$\frac{{\sim}A\lor B\quad A}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$N[$v[$i]]][$v[$j]], $designated) and in_array($v[$i], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor{\sim}B\quad B}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$i]][$N[$v[$j]]], $designated) and in_array($v[$j], $designated)) {
				if (!in_array($v[$i], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>&or; elim. (with conditionals)
<tr><td valign='top'>$$\frac{A\lor B\quad A\to C\quad B\to C}C$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($C[$v[$i]][$v[$k]], $designated) and in_array($C[$v[$j]][$v[$k]], $designated)) {
					if (!in_array($v[$k], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Associativity of &or;
<tr><td valign='top'>$$\frac{(A\lor B)\lor C}{A\lor(B\lor C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($A[$A[$v[$i]][$v[$j]]][$v[$k]], $designated))
			if (!in_array($A[$v[$i]][$A[$v[$j]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor(B\lor C)}{(A\lor B)\lor C}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($A[$v[$i]][$A[$v[$j]][$v[$k]]], $designated))
			if (!in_array($A[$A[$v[$i]][$v[$j]]][$v[$k]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Constructive dilemma
<tr><td valign='top'>$$\frac{A\lor B\quad A\to C\quad B\to D}{C\lor D}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				for ($l = 0; $l < $n; $l++)
				if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($C[$v[$i]][$v[$k]], $designated) and in_array($C[$v[$j]][$v[$l]], $designated)) {
					if (!in_array($A[$v[$k]][$v[$l]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k].",<br>\n<i>D</i>&nbsp;=&nbsp;".$v[$l];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor B\quad A\to C\quad B\to D}{D\lor C}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				for ($l = 0; $l < $n; $l++)
				if (in_array($A[$v[$i]][$v[$j]], $designated) and in_array($C[$v[$i]][$v[$k]], $designated) and in_array($C[$v[$j]][$v[$l]], $designated)) {
					if (!in_array($A[$v[$l]][$v[$k]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k].",<br>\n<i>D</i>&nbsp;=&nbsp;".$v[$l];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Destructive dilemma
<tr><td valign='top'>$$\frac{A\to C\quad B\to D\quad{\sim}C\lor{\sim}D}{{\sim}A\lor{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				for ($l = 0; $l < $n; $l++)
				if (in_array($C[$v[$i]][$v[$k]], $designated) and in_array($C[$v[$j]][$v[$l]], $designated) and in_array($A[$N[$v[$k]]][$N[$v[$l]]], $designated)) {
					if (!in_array($A[$N[$v[$i]]][$N[$v[$j]]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k].",<br>\n<i>D</i>&nbsp;=&nbsp;".$v[$l];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to C\quad B\to D\quad{\sim}C\lor{\sim}D}{{\sim}B\lor{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				for ($l = 0; $l < $n; $l++)
				if (in_array($C[$v[$i]][$v[$k]], $designated) and in_array($C[$v[$j]][$v[$l]], $designated) and in_array($A[$N[$v[$k]]][$N[$v[$l]]], $designated)) {
					if (!in_array($A[$N[$v[$j]]][$N[$v[$i]]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k].",<br>\n<i>D</i>&nbsp;=&nbsp;".$v[$l];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><hr>
<tr><td colspan='2' align='center'><i><b>De Morgan's laws
<tr><td valign='top'>$$\frac{{\sim}(A\;\&\;B)}{{\sim}A\lor{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$K[$v[$i]][$v[$j]]], $designated)) {
				if (!in_array($A[$N[$v[$i]]][$N[$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}A\lor{\sim}B}{{\sim}(A\;\&\;B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$N[$v[$i]]][$N[$v[$j]]], $designated)) {
				if (!in_array($N[$K[$v[$i]][$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}(A\lor B)}{{\sim}A\;\&\;{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$A[$v[$i]][$v[$j]]], $designated)) {
				if (!in_array($K[$N[$v[$i]]][$N[$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}A\;\&\;{\sim}B}{{\sim}(A\lor B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$N[$v[$i]]][$N[$v[$j]]], $designated)) {
				if (!in_array($N[$A[$v[$i]][$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Distributivity
<tr><td valign='top'>$$\frac{A\;\&\;(B\lor C)}{(A\;\&\;B)\lor(A\;\&\;C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($K[$v[$i]][$A[$v[$j]][$v[$k]]], $designated))
			if (!in_array($A[$K[$v[$i]][$v[$j]]][$K[$v[$i]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor(B\;\&\;C)}{(A\lor B)\;\&\;(A\lor C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($A[$v[$i]][$K[$v[$j]][$v[$k]]], $designated))
			if (!in_array($K[$A[$v[$i]][$v[$j]]][$A[$v[$i]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(B\lor C)\;\&\;A}{(B\;\&\;A)\lor(C\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($K[$A[$v[$j]][$v[$k]]][$v[$i]], $designated))
			if (!in_array($A[$K[$v[$j]][$v[$i]]][$K[$v[$k]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(B\;\&\;C)\lor A}{(B\lor A)\;\&\;(C\lor A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($A[$K[$v[$j]][$v[$k]]][$v[$i]], $designated))
			if (!in_array($K[$A[$v[$j]][$v[$i]]][$A[$v[$k]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(A\;\&\;B)\lor(A\;\&\;C)}{A\;\&\;(B\lor C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($A[$K[$v[$i]][$v[$j]]][$K[$v[$i]][$v[$k]]], $designated))
			if (!in_array($K[$v[$i]][$A[$v[$j]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(A\lor B)\;\&\;(A\lor C)}{A\lor(B\;\&\;C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($K[$A[$v[$i]][$v[$j]]][$A[$v[$i]][$v[$k]]], $designated))
			if (!in_array($A[$v[$i]][$K[$v[$j]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(B\;\&\;A)\lor(C\;\&\;A)}{(B\lor C)\;\&\;A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($A[$K[$v[$j]][$v[$i]]][$K[$v[$k]][$v[$i]]], $designated))
			if (!in_array($K[$A[$v[$j]][$v[$k]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(B\lor A)\;\&\;(C\lor A)}{(B\;\&\;C)\lor A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($K[$A[$v[$j]][$v[$i]]][$A[$v[$k]][$v[$i]]], $designated))
			if (!in_array($A[$K[$v[$j]][$v[$k]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><hr>
<tr><td colspan='2' align='center'><i><b>Reflexivity of &rarr;
<tr><td valign='top'>$$\overline{A\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$v[$i]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Irreflexivity of &rarr;
<tr><td valign='top'>$$\overline{{\sim}(A\to A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$C[$v[$i]][$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Aristotle's theses
<tr><td valign='top'>$$\overline{{\sim}(A\to{\sim}A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$C[$v[$i]][$N[$v[$i]]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{{\sim}({\sim}A\to A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$C[$N[$v[$i]]][$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Modus ponens, &rarr; elim.
<tr><td valign='top'>$$\frac{A\to B\quad A}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated) and in_array($v[$i], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Modus tollens
<tr><td valign='top'>$$\frac{A\to B\quad{\sim}B}{{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated) and in_array($N[$v[$j]], $designated)) {
				if (!in_array($N[$v[$i]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Positive paradox
<tr><td valign='top'>$$\overline{B\to(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$v[$j]][$C[$v[$i]][$v[$j]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Vacuous truth
<tr><td valign='top'>$$\overline{{\sim}A\to(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$N[$v[$i]]][$C[$v[$i]][$v[$j]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Contrapositive
<tr><td valign='top'>$$\frac{{\sim}B\to{\sim}A}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$N[$v[$j]]][$N[$v[$i]]], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\to B}{{\sim}B\to{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($C[$N[$v[$j]]][$N[$v[$i]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Symmetry of &rarr;
<tr><td valign='top'>$$\frac{A\to B}{B\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($C[$v[$j]][$v[$i]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><b><i>Conjunctive conditional
<tr><td valign='top'>$$\frac{A\quad B}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$i], $designated) and in_array($v[$j], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to B}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($v[$i], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to B}B$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($v[$j], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Standard conditional
<tr><td valign='top'>$$\frac{A\to B}{{\sim}A\lor B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($A[$N[$v[$i]]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to B}{B\lor{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($A[$v[$j]][$N[$v[$i]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}A\lor B}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$N[$v[$i]]][$v[$j]], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{B\lor{\sim}A}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($A[$v[$j]][$N[$v[$i]]], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to B}{{\sim}(A\;\&\;{\sim}B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($N[$K[$v[$i]][$N[$v[$j]]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to B}{{\sim}({\sim}B\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($N[$K[$N[$v[$j]]][$v[$i]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}(A\;\&\;{\sim}B)}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$K[$v[$i]][$N[$v[$j]]]], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}({\sim}B\;\&\;A)}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$K[$N[$v[$j]]][$v[$i]]], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Standard conditional negation
<tr><td valign='top'>$$\frac{{\sim}(A\to B)}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
				if (!in_array($v[$i], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}(A\to B)}{{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
				if (!in_array($N[$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}(A\to B)}{A\;\&\;{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
				if (!in_array($K[$v[$i]][$N[$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\;\&\;{\sim}B}{{\sim}(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($K[$v[$i]][$N[$v[$j]]], $designated)) {
				if (!in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\quad{\sim}B}{{\sim}(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($v[$i], $designated) and in_array($N[$v[$j]], $designated)) {
				if (!in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Associativity of &rarr;
<tr><td valign='top'>$$\frac{(A\to B)\to C}{A\to(B\to C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($C[$C[$v[$i]][$v[$j]]][$v[$k]], $designated))
			if (!in_array($C[$v[$i]][$C[$v[$j]][$v[$k]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to(B\to C)}{(A\to B)\to C}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++)
			if (in_array($C[$v[$i]][$C[$v[$j]][$v[$k]]], $designated))
			if (!in_array($C[$C[$v[$i]][$v[$j]]][$v[$k]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Boethius's theses
<tr><td valign='top'>$$\overline{(A\to B)\to{\sim}(A\to{\sim}B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$C[$v[$i]][$v[$j]]][$N[$C[$v[$i]][$N[$v[$j]]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\to{\sim}B)\to{\sim}(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$C[$v[$i]][$N[$v[$j]]]][$N[$C[$v[$i]][$v[$j]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Boethius's theses (rule form)
<tr><td valign='top'>$$\frac{A\to B}{{\sim}(A\to{\sim}B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($N[$C[$v[$i]][$N[$v[$j]]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\to{\sim}B}{{\sim}(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$N[$v[$j]]], $designated)) {
				if (!in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Reciprocal Boethius's theses
<tr><td valign='top'>$$\overline{{\sim}(A\to{\sim}B)\to(A\to B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$N[$C[$v[$i]][$N[$v[$j]]]]][$C[$v[$i]][$v[$j]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{{\sim}(A\to B)\to(A\to{\sim}B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$N[$C[$v[$i]][$v[$j]]]][$C[$v[$i]][$N[$v[$j]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Reciprocal Boethius's theses (rule form)
<tr><td valign='top'>$$\frac{{\sim}(A\to{\sim}B)}{A\to B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$C[$v[$i]][$N[$v[$j]]]], $designated)) {
				if (!in_array($C[$v[$i]][$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{{\sim}(A\to B)}{A\to{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($N[$C[$v[$i]][$v[$j]]], $designated)) {
				if (!in_array($C[$v[$i]][$N[$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Peirce's law
<tr><td valign='top'>$$\overline{((A\to B)\to A)\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$C[$C[$v[$i]][$v[$j]]][$v[$i]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Axiom of relativity
<tr><td valign='top'>$$\overline{((A\to B)\to B)\to A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($C[$C[$C[$v[$i]][$v[$j]]][$v[$j]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Absorption
<tr><td valign='top'>$$\frac{A\to B}{A\to(A\;\&\;B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($C[$v[$i]][$K[$v[$i]][$v[$j]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\to B}{A\to(B\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated)) {
				if (!in_array($C[$v[$i]][$K[$v[$j]][$v[$i]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>Abelard's theses
<tr><td valign='top'>$$\overline{{\sim}((A\to B)\;\&\;({\sim}A\to B))}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($N[$K[$C[$v[$i]][$v[$j]]][$C[$N[$v[$i]]][$v[$j]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}(({\sim}A\to B)\;\&\;(A\to B))}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($N[$K[$C[$N[$v[$i]]][$v[$j]]][$C[$v[$i]][$v[$j]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}((A\to B)\;\&\;(A\to{\sim}B))}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($N[$K[$C[$v[$i]][$v[$j]]][$C[$v[$i]][$N[$v[$j]]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}((A\to{\sim}B)\;\&\;(A\to B))}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (!in_array($N[$K[$C[$v[$i]][$N[$v[$j]]]][$C[$v[$i]][$v[$j]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Exportation/importation (currying)
<tr><td valign='top'>$$\frac{A\to(B\to C)}{(A\;\&\;B)\to C}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				if (in_array($C[$v[$i]][$C[$v[$j]][$v[$k]]], $designated)) {
					if (!in_array($C[$K[$v[$i]][$v[$j]]][$v[$k]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(A\;\&\;B)\to C}{A\to(B\to C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				if (in_array($C[$K[$v[$i]][$v[$j]]][$v[$k]], $designated)) {
					if (!in_array($C[$v[$i]][$C[$v[$j]][$v[$k]]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{(A\to(B\to C))\to((A\;\&\;B)\to C)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				if (!in_array($C[$C[$v[$i]][$C[$v[$j]][$v[$k]]]][$C[$K[$v[$i]][$v[$j]]][$v[$k]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{((A\;\&\;B)\to C)\to(A\to(B\to C))}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				if (!in_array($C[$C[$K[$v[$i]][$v[$j]]][$v[$k]]][$C[$v[$i]][$C[$v[$j]][$v[$k]]]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>Hypothetical syllogism
<tr><td valign='top'>$$\frac{A\to B\quad B\to C}{A\to C}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			for ($k = 0; $k < $n; $k++) {
				if (in_array($C[$v[$i]][$v[$j]], $designated) and in_array($C[$v[$j]][$v[$k]], $designated)) {
					if (!in_array($C[$v[$i]][$v[$k]], $designated)) {
						$bool = False;
						$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j].",<br>\n<i>C</i>&nbsp;=&nbsp;".$v[$k];
					}
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><b><i>Affirming the consequent
<tr><td valign='top'>$$\frac{A\to B\quad B}A$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated) and in_array($v[$j], $designated)) {
				if (!in_array($v[$i], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><b><i>Negating the antecedent
<tr><td valign='top'>$$\frac{A\to B\quad{\sim}A}{{\sim}B}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++) {
			if (in_array($C[$v[$i]][$v[$j]], $designated) and in_array($N[$v[$i]], $designated)) {
				if (!in_array($N[$v[$j]], $designated)) {
					$bool = False;
					$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
				}
			}
		}
	}
	print "<b>Valid&nbsp;?</b> ";
	if ($bool) {
		print "Yes&nbsp;!";
	} else {
		print "Nope.<br>\n<b>Counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td colspan='2' align='center'><hr>

</table>

<?php }} ?>
