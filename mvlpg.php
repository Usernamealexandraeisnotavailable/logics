<title>Many-Valued Logic PlayGround</title>
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
<i>See also&nbsp;: <a href="https://users.cecs.anu.edu.au/~jks/magic.html" target="_blank">MaGIC</a> (Slaney)</i>&nbsp;; <a href="https://github.com/Usernamealexandraeisnotavailable/logics/blob/main/mvlpg.php" target="_blank">Source code</a>
<nav>
  <ul>
    <li class="deroulant"><b><i>examples</i> &#9660;</b></a>
	  <center>
      <ul class="sous">
		<li><a href="?n=2&v0=True&d0=y&v1=False&d1=n&description=Standard+logic+(Frege%2C+1879+%3B+Russell+%26+Whitehead%2C+1910)">Standard</a> (Frege, 1879&nbsp;; Russell &amp; Whitehead, 1910)
		<li><a href="?n=1&v0=both&d0=y&description=Trivial+logic+only+has+one+truth+value,+which+happens+to+be+designated.">Trivial</a>
		<li><a href="?n=1&v0=neither&d0=n&description=Nihilism%2C+at+least+in+this+context%2C+is+what+happens+when+there+is+no+designated+value.+Note+that+there+could+also+be+a+stronger+anti-inferential+nihilism+that+admits+no+rule.">Nihilistic</a>
		<li><a href="?n=3&v0=1&d0=y&v1=1%2F2&d1=n&v2=0&d2=n&N0=0&N1=0&N2=1&K0%3B0=1&K0%3B1=1%2F2&K0%3B2=0&K1%3B0=1%2F2&K1%3B1=1%2F2&K1%3B2=0&K2%3B0=0&K2%3B1=0&K2%3B2=0&A0%3B0=1&A0%3B1=1&A0%3B2=1&A1%3B0=1&A1%3B1=1%2F2&A1%3B2=1%2F2&A2%3B0=1&A2%3B1=1%2F2&A2%3B2=0&C0%3B0=1&C0%3B1=1%2F2&C0%3B2=0&C1%3B0=1&C1%3B1=1&C1%3B2=0&C2%3B0=1&C2%3B1=1&C2%3B2=1&description=This+is+an+example+of+a+3-valued+Heyting+algebra.">3-valued Heyting algebra</a>
		<li><a href="?n=4&v0=11&d0=y&v1=10&d1=n&v2=01&d2=n&v3=00&d3=n&N0=00&N1=01&N2=10&N3=11&K0%3B0=11&K0%3B1=10&K0%3B2=01&K0%3B3=00&K1%3B0=10&K1%3B1=10&K1%3B2=00&K1%3B3=00&K2%3B0=01&K2%3B1=00&K2%3B2=01&K2%3B3=00&K3%3B0=00&K3%3B1=00&K3%3B2=00&K3%3B3=00&A0%3B0=11&A0%3B1=11&A0%3B2=11&A0%3B3=11&A1%3B0=11&A1%3B1=10&A1%3B2=11&A1%3B3=10&A2%3B0=11&A2%3B1=11&A2%3B2=01&A2%3B3=01&A3%3B0=11&A3%3B1=10&A3%3B2=01&A3%3B3=00&C0%3B0=11&C0%3B1=10&C0%3B2=01&C0%3B3=00&C1%3B0=11&C1%3B1=11&C1%3B2=01&C1%3B3=01&C2%3B0=11&C2%3B1=10&C2%3B2=11&C2%3B3=10&C3%3B0=11&C3%3B1=11&C3%3B2=11&C3%3B3=11&description=This+is+an+example+of+a+4-valued+Boolean+algebra.">4-valued Boolean algebra</a>
		<li><a href="?n=3&v0=F&d0=n&v1=U&d1=n&v2=T&d2=y&N0=T&N1=U&N2=F&K0%3B0=F&K0%3B1=F&K0%3B2=F&K1%3B0=F&K1%3B1=U&K1%3B2=U&K2%3B0=F&K2%3B1=U&K2%3B2=T&A0%3B0=F&A0%3B1=U&A0%3B2=T&A1%3B0=U&A1%3B1=U&A1%3B2=T&A2%3B0=T&A2%3B1=T&A2%3B2=T&C0%3B0=T&C0%3B1=T&C0%3B2=T&C1%3B0=U&C1%3B1=U&C1%3B2=T&C2%3B0=F&C2%3B1=U&C2%3B2=T&description=Strong+Kleene+logic+of+indeterminacy+%28Kleene%2C+1938%29">Strong Kleene</a> (Kleene, 1938)
		<li><a href="?n=3&v0=F&d0=n&v1=B&d1=y&v2=T&d2=y&N0=T&N1=B&N2=F&K0%3B0=F&K0%3B1=F&K0%3B2=F&K1%3B0=F&K1%3B1=B&K1%3B2=B&K2%3B0=F&K2%3B1=B&K2%3B2=T&A0%3B0=F&A0%3B1=B&A0%3B2=T&A1%3B0=B&A1%3B1=B&A1%3B2=T&A2%3B0=T&A2%3B1=T&A2%3B2=T&C0%3B0=T&C0%3B1=T&C0%3B2=T&C1%3B0=B&C1%3B1=B&C1%3B2=T&C2%3B0=F&C2%3B1=B&C2%3B2=T&description=LP+%28Priest%2C+1979%29%0D%0A%0D%0ALP+stands+for+logic+of+paradox"><b>LP</b></a> (Priest, 1979)
		<li><a href="?n=4&v0=1&d0=y&v1=2&d1=y&v2=3&d2=n&v3=4&d3=n&N0=4&N1=3&N2=2&N3=1&K0%3B0=1&K0%3B1=2&K0%3B2=3&K0%3B3=4&K1%3B0=1&K1%3B1=2&K1%3B2=4&K1%3B3=3&K2%3B0=3&K2%3B1=3&K2%3B2=3&K2%3B3=4&K3%3B0=4&K3%3B1=3&K3%3B2=4&K3%3B3=3&A0%3B0=2&A0%3B1=1&A0%3B2=2&A0%3B3=1&A1%3B0=1&A1%3B1=2&A1%3B2=2&A1%3B3=2&A2%3B0=2&A2%3B1=1&A2%3B2=3&A2%3B3=4&A3%3B0=1&A3%3B1=2&A3%3B2=3&A3%3B3=4&C0%3B0=1&C0%3B1=4&C0%3B2=3&C0%3B3=4&C1%3B0=4&C1%3B1=1&C1%3B2=4&C1%3B3=3&C2%3B0=1&C2%3B1=4&C2%3B2=1&C2%3B3=4&C3%3B0=4&C3%3B1=1&C3%3B2=4&C3%3B3=1&description=CC1+%28Angell%2C+1962+%3B+McCall%2C+1966%29"><b>CC1</b></a> (Angell, 1962&nbsp;; McCall, 1966)
		<li><a href="?n=3&v0=T&d0=y&v1=F&d1=n&v2=--&d2=y&N0=F&N1=T&N2=--&K0%3B0=T&K0%3B1=F&K0%3B2=--&K1%3B0=F&K1%3B1=F&K1%3B2=F&K2%3B0=--&K2%3B1=F&K2%3B2=--&A0%3B0=T&A0%3B1=T&A0%3B2=T&A1%3B0=T&A1%3B1=F&A1%3B2=--&A2%3B0=T&A2%3B1=--&A2%3B2=--&C0%3B0=T&C0%3B1=F&C0%3B2=--&C1%3B0=--&C1%3B1=--&C1%3B2=--&C2%3B0=T&C2%3B1=F&C2%3B2=--&description=CN+%28Cantwell%2C+2008%29%2C+aka.+MC+%28Wansing%2C+2005%29%0D%0A%0D%0ACN+stands+for+conditional+negation"><b>CN</b></a> (Cantwell, 2008), aka. <b>MC</b> (Wansing, 2005)
		<li><a href="?n=3&v0=1&d0=y&v1=2&d1=n&v2=3&d2=n&N0=3&N1=1&N2=1&K0%3B0=1&K0%3B1=2&K0%3B2=3&K1%3B0=2&K1%3B1=2&K1%3B2=3&K2%3B0=3&K2%3B1=3&K2%3B2=3&A0%3B0=1&A0%3B1=1&A0%3B2=1&A1%3B0=1&A1%3B1=2&A1%3B2=2&A2%3B0=1&A2%3B1=2&A2%3B2=3&C0%3B0=1&C0%3B1=2&C0%3B2=3&C1%3B0=2&C1%3B1=2&C1%3B2=2&C2%3B0=2&C2%3B1=2&C2%3B2=2&description=MRS%5EP+%28Estrada-González%2C+2008%29"><b>MRS<sup><i>P</i></sup></b></a> (Estrada-González, 2008)
		<li><a href="?n=4&v0=true&d0=y&v1=both&d1=y&v2=neither&d2=n&v3=false&d3=n&description=FDE+(Dunn%2C+1976+%3B+Belnap%2C+1977)%0D%0A%0D%0AFDE+has+no+⊃%2C+so+i+just+engineered+one+out+that+kinda+makes+sense.&N0=false&N1=both&N2=neither&N3=true&K0%3B0=true&K0%3B1=both&K0%3B2=neither&K0%3B3=false&K1%3B0=both&K1%3B1=both&K1%3B2=false&K1%3B3=false&K2%3B0=neither&K2%3B1=false&K2%3B2=neither&K2%3B3=false&K3%3B0=false&K3%3B1=false&K3%3B2=false&K3%3B3=false&A0%3B0=true&A0%3B1=true&A0%3B2=true&A0%3B3=true&A1%3B0=true&A1%3B1=both&A1%3B2=true&A1%3B3=both&A2%3B0=true&A2%3B1=true&A2%3B2=neither&A2%3B3=neither&A3%3B0=true&A3%3B1=both&A3%3B2=neither&A3%3B3=false&C0%3B0=true&C0%3B1=false&C0%3B2=false&C0%3B3=false&C1%3B0=true&C1%3B1=both&C1%3B2=neither&C1%3B3=false&C2%3B0=true&C2%3B1=neither&C2%3B2=both&C2%3B3=false&C3%3B0=true&C3%3B1=true&C3%3B2=true&C3%3B3=true"><b>FDE</b></a> (Dunn, 1976&nbsp;; Belnap, 1977)
		<li><a href="?n=3&v0=T&d0=y&v1=*&d1=y&v2=F&d2=n&N0=F&N1=*&N2=T&K0%3B0=T&K0%3B1=*&K0%3B2=F&K1%3B0=*&K1%3B1=*&K1%3B2=F&K2%3B0=F&K2%3B1=F&K2%3B2=F&A0%3B0=T&A0%3B1=T&A0%3B2=T&A1%3B0=T&A1%3B1=*&A1%3B2=*&A2%3B0=T&A2%3B1=*&A2%3B2=F&C0%3B0=*&C0%3B1=F&C0%3B2=F&C1%3B0=*&C1%3B1=*&C1%3B2=F&C2%3B0=*&C2%3B1=*&C2%3B2=*&description=M3V+%28Mortensen%2C+1984+%3B+McCall%2C+2012%29%0D%0A%0D%0AMortensen’s+3-valued+logic."><b>M3V</b></a> (Mortensen, 1984&nbsp;; McCall, 2012)
		<li><a href="?n=3&v0=Ω&d0=y&v1=∅&d1=n&v2=N%2FA&d2=n&description=-+This+logic+is+inspired+by+conditional+probability%0D%0A-+Ω+is+a+certain+event%2C+∅+is+an+impossible+event%0D%0A-+By+definition%2C+P%28B%7CA%29+%3D+P%28B%26A%29%2FP%28A%29%2C+so+if+A%3D∅%2C+that+yields+a+ZeroDivisionError%2C+hence+the+N%2FA+value&N0=∅&N1=Ω&N2=N%2FA&K0%3B0=Ω&K0%3B1=∅&K0%3B2=N%2FA&K1%3B0=∅&K1%3B1=∅&K1%3B2=N%2FA&K2%3B0=N%2FA&K2%3B1=N%2FA&K2%3B2=N%2FA&A0%3B0=Ω&A0%3B1=Ω&A0%3B2=N%2FA&A1%3B0=Ω&A1%3B1=∅&A1%3B2=N%2FA&A2%3B0=N%2FA&A2%3B1=N%2FA&A2%3B2=N%2FA&C0%3B0=Ω&C0%3B1=∅&C0%3B2=N%2FA&C1%3B0=N%2FA&C1%3B1=N%2FA&C1%3B2=N%2FA&C2%3B0=N%2FA&C2%3B1=N%2FA&C2%3B2=N%2FA">&Omega;&ndash;&varnothing; conditional probabilities</a>
		<li>Example of 3-valued <a href="?n=3&v0=3&d0=y&v1=2&d1=n&v2=1&d2=n&description=This+is+an+example+of+a+3-valued+abelian+logic+%28ie.+satisfies+the+axiom+of+relativity%2C+cf.+Meyer+%26+Slaney%29%2C+where+p⊃q+stands+for+q-p+in+the+abelian+group+Z%2F3Z.&N0=1&N1=1&N2=3&K0%3B0=3&K0%3B1=2&K0%3B2=1&K1%3B0=2&K1%3B1=2&K1%3B2=1&K2%3B0=1&K2%3B1=1&K2%3B2=1&A0%3B0=3&A0%3B1=3&A0%3B2=3&A1%3B0=3&A1%3B1=2&A1%3B2=2&A2%3B0=3&A2%3B1=2&A2%3B2=1&C0%3B0=3&C0%3B1=2&C0%3B2=1&C1%3B0=1&C1%3B1=3&C1%3B2=2&C2%3B0=2&C2%3B1=1&C2%3B2=3">abelian logic</a> (cf. Meyer &amp; Slaney)
	  </ul>
	</li>
  </ul>
</nav>
<table>
<tr><td>
<ul>
<tr><td><form method='get'><b>number of values :</b> <input type='number' value='<?=$n;?>' name='n' min='<?=$min_n;?>' max='<?=$max_n;?>'> <input type='submit' value='submit'></form>
<tr><td><form method='get'><input type="hidden" name="n" value="<?=$n;?>"><b>values :</b> <ul><?php
for ($i = 0; $i < $n; $i++) {
	if (in_array($v[$i], $designated)) {
		print "\n	<li><input type='text' value='".$v[$i]."' name='v$i'><br>\n	designated ? <input type='radio' name='d$i' value='y' checked> yes / <input type='radio' name='d$i' value='n'> no";
	} else {
		print "\n	<li><input type='text' value='".$v[$i]."' name='v$i'><br>\n	designated ? <input type='radio' name='d$i' value='y'> yes / <input type='radio' name='d$i' value='n' checked> no";
	}
}
?></ul>
<b>description :</b><br>
<textarea name="description" style="width: 100%; height: 100px; font-family: Times New Roman"><?=$description;?></textarea><br>
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
<input type="hidden" name="description" value="<?=$description;?>">
<table>
<tr><td><b>negation :
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
<tr><td><b>conjunction :
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
<tr><td><b>disjunction :
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
<tr><td><b>implication :
		<table cellspacing="0" b><tr><td b><i>A</i>&nbsp;&supset;&nbsp;<i>B</i><?php
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
<center><b>custom inference test</b></center>
<hr>
<b>number of variables&nbsp;:</b> <input type="number" name="inf_nv" min="1" max="5" value="<?=$_GET["inf_nv"];?>"><br>
<b>number of premises&nbsp;:</b> <input type="number" name="inf_np" min="0" max="5" value="<?=$_GET["inf_np"];?>"><br>
<b>number of conclusions&nbsp;:</b> <input type="number" name="inf_nc" min="1" max="5" value="<?=$_GET["inf_nc"];?>"><br>
<input type="submit" value="submit">
</form><br><br>
&bullet; wff are case-sensitive, but space-insensitive.<br>
&bullet; <span class='wff'>A</span><?php
for ($i = 1; $i < $_GET["inf_nv"]; $i++) {
	print ", <span class='wff'>".['A','B','C','D','E'][$i]."</span>";
}
if ($_GET["inf_nv"] == 1) print " is a wff.";
else print " are wff.";
?><br>
&bullet; if <i class='wff'>&alpha;</i> is a wff, then so is <span class='wff'>not(<i>&alpha;</i>)</span>.<br>
&bullet; if <i class='wff'>&alpha;</i> and <i class='wff'>&beta;</i> are wff, then so is <span class='wff'>and(<i>&alpha;</i>, <i>&beta;</i>)</span>.<br>
&bullet; if <i class='wff'>&alpha;</i> and <i class='wff'>&beta;</i> are wff, then so is <span class='wff'>or(<i>&alpha;</i>, <i>&beta;</i>)</span>.<br>
&bullet; if <i class='wff'>&alpha;</i> and <i class='wff'>&beta;</i> are wff, then so is <span class='wff'>implies(<i>&alpha;</i>, <i>&beta;</i>)</span>.<br>
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
<b>premise(s)&nbsp;:</b><br>
<?php
for ($i = 0; $i < $_GET["inf_np"]; $i++)
	if (isset($_GET["inf_p$i"]))
	print "<input type='text' name='inf_p$i' value='".$_GET["inf_p$i"]."'><br>\n";
	else
	print "<input type='text' name='inf_p$i'><br>\n";
?><br>
<b>conclusion(s)&nbsp;:</b><br>
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
	return "($A\\supset $B)";
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
print "<tr><td valign='top' colspan='2' align='center'><b><i>custom inference
<tr><td valign='top'>".latex($prem, $conc)."
<td valign='center'>";
eval("\$bool = True;
\$counter = '<hr>\n';
".loops($_GET["inf_nv"]).cond($prem, $conc)." {
	\$bool = False;
	\$counter .= ".counter_format($_GET["inf_nv"])."
	\$counter .= '<hr>\n';
}
print \"<b>valid&nbsp;?</b> \";
if (\$bool) {
	print \"yes&nbsp;!\";
} else {
	print \"nope.<br>\\n<b>counter-example(s)&nbsp;:</b><br>\\n\$counter\";
}");
}
?></pre>
<?php } else { ?>
<form method="get">
<center><b>custom inference test</b></center>
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
<b>number of variables&nbsp;:</b> <input type="number" name="inf_nv" min="1" max="5"><br>
<b>number of premises&nbsp;:</b> <input type="number" name="inf_np" min="0" max="5"><br>
<b>number of conclusions&nbsp;:</b> <input type="number" name="inf_nc" min="1" max="5"><br>
<input type="submit" value="submit">
</form>
<?php } ?>
</table>








<hr>
<table cellspacing="20px">

<tr><td colspan='2' align='center'><b>inference tests' results</b>
<hr>

<tr><td colspan='2' align='center'><i><b>double negation

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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{A\supset{\sim}{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$N[$N[$v[$i]]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{{\sim}{\sim}A\supset A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$N[$N[$v[$i]]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>self-negation

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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{A\supset{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$N[$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{{\sim}A\supset A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$N[$v[$i]]][$v[$i]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><hr>

<tr><td colspan='2' align='center'><i><b>idempotency of &amp;
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{A\supset(A\;\&\;A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$K[$v[$i]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{(A\;\&\;A)\supset A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$K[$v[$i]][$v[$i]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>law of noncontradiction
<tr><td valign='top'>$$\overline{{\sim}(A\;\&\;{\sim}A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$K[$v[$i]][$N[$v[$i]]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>simplification, &amp; elim.
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\;\&\;B)\supset A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\;\&\;B)\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>symmetry of &amp;
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{(A\;\&\;B)\supset(B\;\&\;A)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>ex contradictione quodlibet
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\;\&\;{\sim}A)\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{({\sim}A\;\&\;A)\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>associativity of &amp;
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>


<tr><td colspan='2' align='center'><hr>
<tr><td colspan='2' align='center'><i><b>idempotency of &or;
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{A\supset(A\lor A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$A[$v[$i]][$v[$i]]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td valign='top'>$$\overline{(A\lor A)\supset A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$A[$v[$i]][$v[$i]]][$v[$i]], $designated)) {
				$bool = False;
				$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>law of excluded middle
<tr><td valign='top'>$$\overline{A\lor{\sim}A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($A[$v[$i]][$N[$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{A\supset(B\lor{\sim}B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++)
		if (!in_array($C[$v[$i]][$A[$v[$j]][$N[$v[$j]]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{A\supset({\sim}B\lor B)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $n; $j++)
		if (!in_array($C[$v[$i]][$A[$N[$v[$j]]][$v[$j]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i].",<br>\n<i>B</i>&nbsp;=&nbsp;".$v[$j];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>addition, &or; intro.
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{A\supset(A\lor B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{B\supset(A\lor B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>exclusive &or; intro.
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>affirming a disjunct, exclusive &or; elim.
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>dual nd &or; elim.
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>symmetry of &or;
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{(A\lor B)\supset(B\lor A)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>disjunctive syllogism
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>disjunctive syllogism (standard implication form)
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>&or; elim. (with conditionals)
<tr><td valign='top'>$$\frac{A\lor B\quad A\supset C\quad B\supset C}C$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>associativity of &or;
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>constructive dilemma
<tr><td valign='top'>$$\frac{A\lor B\quad A\supset C\quad B\supset D}{C\lor D}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\lor B\quad A\supset C\quad B\supset D}{D\lor C}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>destructive dilemma
<tr><td valign='top'>$$\frac{A\supset C\quad B\supset D\quad{\sim}C\lor{\sim}D}{{\sim}A\lor{\sim}B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset C\quad B\supset D\quad{\sim}C\lor{\sim}D}{{\sim}B\lor{\sim}A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><hr>
<tr><td colspan='2' align='center'><i><b>de morgan's laws
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>distributivity
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><hr>
<tr><td colspan='2' align='center'><i><b>reflexivity of &supset;
<tr><td valign='top'>$$\overline{A\supset A}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($C[$v[$i]][$v[$i]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>irreflexivity of &supset;
<tr><td valign='top'>$$\overline{{\sim}(A\supset A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$C[$v[$i]][$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>aristotle's theses
<tr><td valign='top'>$$\overline{{\sim}(A\supset{\sim}A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$C[$v[$i]][$N[$v[$i]]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{{\sim}({\sim}A\supset A)}$$
	<td valign='center'><?php
	$bool = True;
	for ($i = 0; $i < $n; $i++) {
		if (!in_array($N[$C[$N[$v[$i]]][$v[$i]]], $designated)) {
			$bool = False;
			$counter = "<i>A</i>&nbsp;=&nbsp;".$v[$i];
		}
	}
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>modus ponens, &supset; elim.
<tr><td valign='top'>$$\frac{A\supset B\quad A}B$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>modus tollens
<tr><td valign='top'>$$\frac{A\supset B\quad{\sim}B}{{\sim}A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>positive paradox
<tr><td valign='top'>$$\overline{B\supset(A\supset B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>vacuous truth
<tr><td valign='top'>$$\overline{{\sim}A\supset(A\supset B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>contrapositive
<tr><td valign='top'>$$\frac{{\sim}B\supset{\sim}A}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\supset B}{{\sim}B\supset{\sim}A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>symmetry of &supset;
<tr><td valign='top'>$$\frac{A\supset B}{B\supset A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><b><i>conjunctive conditional
<tr><td valign='top'>$$\frac{A\quad B}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset B}A$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset B}B$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>standard conditional
<tr><td valign='top'>$$\frac{A\supset B}{{\sim}A\lor B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset B}{B\lor{\sim}A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}A\lor B}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{B\lor{\sim}A}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset B}{{\sim}(A\;\&\;{\sim}B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset B}{{\sim}({\sim}B\;\&\;A)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}(A\;\&\;{\sim}B)}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{{\sim}({\sim}B\;\&\;A)}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>associativity of &supset;
<tr><td valign='top'>$$\frac{(A\supset B)\supset C}{A\supset(B\supset C)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset(B\supset C)}{(A\supset B)\supset C}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>boethius's theses
<tr><td valign='top'>$$\overline{(A\supset B)\supset{\sim}(A\supset{\sim}B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{(A\supset{\sim}B)\supset{\sim}(A\supset B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>boethius's theses (rule form)
<tr><td valign='top'>$$\frac{A\supset B}{{\sim}(A\supset{\sim}B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{A\supset{\sim}B}{{\sim}(A\supset B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>reciprocal boethius's theses
<tr><td valign='top'>$$\overline{{\sim}(A\supset{\sim}B)\supset(A\supset B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\overline{{\sim}(A\supset B)\supset(A\supset{\sim}B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>reciprocal boethius's theses (rule form)
<tr><td valign='top'>$$\frac{{\sim}(A\supset{\sim}B)}{A\supset B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td valign='top'>$$\frac{{\sim}(A\supset B)}{A\supset{\sim}B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>axiom of relativity
<tr><td valign='top'>$$\overline{((A\supset B)\supset B)\supset A}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>absorption
<tr><td valign='top'>$$\frac{A\supset B}{A\supset(A\;\&\;B)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{A\supset B}{A\supset(B\;\&\;A)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
	
<tr><td colspan='2' align='center'><i><b>abelard's theses
<tr><td valign='top'>$$\overline{{\sim}((A\supset B)\;\&\;({\sim}A\supset B))}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}(({\sim}A\supset B)\;\&\;({\sim}A\supset B))}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}((A\supset B)\;\&\;(A\supset{\sim}B))}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{{\sim}((A\supset{\sim}B)\;\&\;({\sim}A\supset B))}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>exportation/importation (currying)
<tr><td valign='top'>$$\frac{A\supset(B\supset C)}{(A\;\&\;B)\supset C}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\frac{(A\;\&\;B)\supset C}{A\supset(B\supset C)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{(A\supset(B\supset C))\supset((A\;\&\;B)\supset C)}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td valign='top'>$$\overline{((A\;\&\;B)\supset C)\supset(A\supset(B\supset C))}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><i><b>hypothetical syllogism
<tr><td valign='top'>$$\frac{A\supset B\quad B\supset C}{A\supset C}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><b><i>affirming the consequent
<tr><td valign='top'>$$\frac{A\supset B\quad B}A$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>

<tr><td colspan='2' align='center'><b><i>negating the antecedent
<tr><td valign='top'>$$\frac{A\supset B\quad{\sim}A}{{\sim}B}$$
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
	print "<b>valid&nbsp;?</b> ";
	if ($bool) {
		print "yes&nbsp;!";
	} else {
		print "nope.<br>\n<b>counter-example&nbsp;:</b><br>\n$counter";
	}
	?>
<tr><td colspan='2' align='center'><hr>

</table>

<?php }} ?>
