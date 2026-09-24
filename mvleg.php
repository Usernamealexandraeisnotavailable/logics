<title>Many-Valued Logic Explorer Generator (No AI :3c)</title>
<meta name="description" content="An online tool that takes a proof calculus and designs an SAT solving algorithm to find sound many-valued functional semantics (with eventual user-input restrictions).">
<meta name="author" content="alexandræ">
<script id='MathJax-script' async src='https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js'></script>
<style>
h1 { margin: 0pt; margin-top: 5pt }
body { background-color: black; color: white }
textarea { color: white; background-color: rgb(64,64,64) }
[code] { font-family: Courier }
code { font-family: Courier; background-color: rgb(64,64,64) }
[bordered] { border: solid 1px white }
[bordered] tr td { border: solid 1px white; padding: 5pt; }
a { color: yellow; text-decoration: none }
a:hover { color: orange; text-decoration: underline }
table[main] {
  grid-template-areas: 
  "head-fixed" 
  "body-scrollable";
}
table[main] tbody[main] {
  grid-area: body-scrollable;
  overflow: auto;
  height: calc(100vh - 55px);
}
</style>
<?php
function ENTAILS ($a, $b) {
    return "$a\\vdash $b";
}
function COMMA ($a, $b) {
    return "($a,$b)";
}
function IMPLIES ($a, $b) {
    return '\frac{'.$a.'}{'.$b.'}';
}
function METAAND (...$args) {
    return "\\left(".implode('\quad\textbf{ and }\quad ',$args)."\\right)";
}
function METAOR ($a, $b) {
    return "\\left(".implode('\quad\textbf{ or }\quad ',$args)."\\right)";
}
function METANOT ($a) {
    return "\\textbf{not}\\left($a\\right)";
}
function C ($a, $b) {
    return "($a\\to $b)";
}
function N ($a) {
    return "\\neg $a";
}
function K ($a, $b) {
    return "($a\\mathbin{\\&}$b)";
}
function A ($a, $b) {
    return "($a\\vee $b)";
}
$sanitizedGET = [];
foreach ($_GET as $i => $j) {
    $sanitizedGET[htmlentities($i)] = htmlentities($j);
}

function processed ($i) {
    global $sanitizedGET;
    $variables = explode("\n", str_replace("\r","",$sanitizedGET["var$i"]));
    $variablesWithDollarSigns = [];
    foreach ($variables as $aa => $bb) {
        for ($m = 0; $m < strlen($bb); $m++) {
            if (!in_array($bb[$m], ['a','z','e','r','t','y','u','i','o','p','q','s','d','f','g','h','j','k','l','m','w','x','c','v','b','n'])) {
                print "<h1>".$bb[$m]."</h1>";
                return false;
            }
        }
        $variablesWithDollarSigns[$aa] = "\"".$bb."\"";
    }
    $test = str_replace($variables,$variablesWithDollarSigns,str_replace(["Entails","Comma","Implies","And","Or","Not","Neutral"],["ENTAILS","COMMA","IMPLIES","METAAND","METAOR","METANOT","\"()\""],$sanitizedGET["txt$i"]));
    if (str_replace($variablesWithDollarSigns,"",str_replace([" ","ENTAILS","COMMA","IMPLIES","METAAND","METAOR","METANOT","\"()\"","(",")","C","K","N","A"," ","\r","\n",","],"",$test)) == "") {
        return $test;
    } else {
        print "<meta http-equiv='refresh' content='0; mvleg.php'>";
    }
}
?>
<center>
<table main width="100%">
<tbody main>
<tr width="100%">
<td width="100%" valign="center" align="center" colspan="2">

<h1><a href="mvleg.php">Many-Valued Logic Explorer Generator</a></h1>
Install Python on <a href="https://www.python.org" target="_blank" code>https://www.python.org</a><br>
To install PIP, follow instructions from <a href="https://pip.pypa.io/" target="_blank" code>https://pip.pypa.io/</a><br>
To install Z3, follow instructions from <a href="https://pypi.org/project/z3-solver/" target="_blank" code>https://pypi.org/project/z3-solver/</a><br>
<i>(Source code <a href="https://github.com/Usernamealexandraeisnotavailable/logics/blob/main/mvleg.php" target="_blank">here</a> &bullet; Some basic (sub)structurality <a href="?nam1=Non-triviality&var1=a&txt1=Not%28Entails%28Neutral%2Ca%29%29&typ1=Or&nam2=Associativity&var2=g%0D%0Ad%0D%0Ae%0D%0Aa&txt2=And%28%0D%0A+Implies%28%0D%0A++Entails%28Comma%28g%2CComma%28d%2Ce%29%29%2Ca%29%2C%0D%0A++Entails%28Comma%28Comma%28g%2Cd%29%2Ce%29%2Ca%29%0D%0A+%29%2C%0D%0A+Implies%28%0D%0A++Entails%28Comma%28Comma%28g%2Cd%29%2Ce%29%2Ca%29%2C%0D%0A++Entails%28Comma%28g%2CComma%28d%2Ce%29%29%2Ca%29%0D%0A+%29%0D%0A%29&typ2=And&nam3=Left+neutrality&var3=g%0D%0Aa&txt3=And%28%0D%0A+Implies%28%0D%0A++Entails%28Comma%28Neutral%2Cg%29%2Ca%29%2C%0D%0A++Entails%28g%2Ca%29%0D%0A+%29%2C%0D%0A+Implies%28%0D%0A++Entails%28g%2Ca%29%2C%0D%0A++Entails%28Comma%28Neutral%2Cg%29%2Ca%29%0D%0A+%29%0D%0A%29&typ3=And&nam4=Axiom+rule&var4=a&txt4=Entails%28a%2Ca%29&typ4=And&nam5=Cut+rule&var5=g%0D%0Ad%0D%0Aa%0D%0Ab&txt5=Implies%28%0D%0A+And%28%0D%0A++Entails%28g%2Ca%29%2C%0D%0A++Entails%28Comma%28d%2Ca%29%2Cb%29%0D%0A+%29%2C%0D%0A+Entails%28Comma%28g%2Cd%29%2Cb%29%0D%0A%29&typ5=And&nam6=Permutation+rule&var6=g%0D%0Ad%0D%0Aa&txt6=Implies%28%0D%0A+Entails%28Comma%28g%2Cd%29%2Ca%29%2C%0D%0A+Entails%28Comma%28g%2Cd%29%2Ca%29%0D%0A%29&typ6=And&nam7=Contraction+rule&var7=g%0D%0Aa%0D%0Ab&txt7=Implies%28%0D%0A+Entails%28Comma%28g%2CComma%28a%2Ca%29%29%2Cb%29%2C%0D%0A+Entails%28Comma%28g%2Ca%29%2Cb%29%0D%0A%29&typ7=And&nam8=Weakening+rule&var8=g%0D%0Aa%0D%0Ab&txt8=Implies%28%0D%0A+Entails%28g%2Cb%29%2C%0D%0A+Entails%28Comma%28g%2Ca%29%2Cb%29%0D%0A%29&typ8=And&sub=Submit">here</a>)</i>

<tr style="height: 70%">
<td style="height: 90%; width: 50%" valign="top" align="center">
<div style="overflow:scroll; width:100%; height: 90%">
<h2>Presentation</h2>
<table style="width: 80%"><tr><td style="text-align: justify">
Have a proof calculus and want to show it's non-trivial? Or variable sharing? Or non-symmetric implication? Or basically anything of the form &Gamma;&nvdash;<i>p</i> (existentially or universally)? Sounds like you need a sound (finitely) many-valued model! If one exists, this tool <b>will</b> find it. Guaranteed 100% without LLM hallucination (it uses the Z3 SAT solver, and most importantly, not an ounce of AI). Even this page's HTML, PHP and Python codes are made with my <s>paws</s> <i>hands</i> all the way through (well, given how garbage it is, it better be).
</table>
<h2>Metasyntax</h2>
<?php
foreach ($sanitizedGET as $i => $j) {
    print "<input type='hidden' name='$i' value='$j'>\n";
}
?>
<table bordered cellspacing="0">
<tr><td><b>Encoding</b>
    <td><b>Name</b>
    <td><b>Arity</b>
<tr><td code>Entails
    <td>Entailment (&vdash;)
    <td>Binary (relation)
<tr><td code>Comma
    <td>Comma (,)
    <td>Binary
<tr><td code>Not
    <td>"Not ..."
    <td>Unary
<tr><td code>Implies
    <td>"If... then..."
    <td>Binary
<tr><td code>And
    <td>"... and ..."
    <td>Any arity
<tr><td code>Or
    <td>"... and/or ..."
    <td>Any arity
</table>

<h2>Syntax</h2>
<table bordered cellspacing="0">
<tr><td><b>Encoding</b>
    <td><b>Name</b>
    <td><b>Arity</b>
<tr><td code>N
    <td>Negation (~)
    <td>Unary
<tr><td code>C
    <td>Implication (→)
    <td>Binary
<tr><td code>K
    <td>Conjunction (&)
    <td>Binary
<tr><td code>A
    <td>Disjunction (∨)
    <td>Binary
</table>

<h2>Rules</h2>
<?php
$n = 1;
while (isset($_GET["typ$n"])) {
    $n++;
}
?>
<form method="get" action="#And<?=max(1,$n-1);?>">
<table bordered cellspacing="0" style="width: 80%">
<tr><td align="center"><i><b>Variables</b><br><small>(separated by linebreaks)
    <td align="center"><i><b>Code

<?php
for ($i = 1; $i < $n; $i++) {
?>

<tr><td colspan="2" style="text-align: center; border-bottom: 0pt">
        $$<?php
        eval("print ".processed($i).";");
        ?>$$
        Name: <input type="text" name="nam<?=$i;?>" value="<?=$sanitizedGET["nam$i"];?>">
<tr><td style="width: 20%; text-align: center; border-right: 0pt; border-top: 0pt; border-bottom: 0pt">
        <textarea name="var<?=$i;?>" style="width: 100%; height: 100pt"><?=$sanitizedGET["var$i"];?></textarea>
    <td style="width: 80%; text-align: center; border-left: 0pt; border-top: 0pt; border-bottom: 0pt">
        <textarea style="width: 100%; height: 100pt" name="txt<?=$i;?>" onkeydown="if(event.keyCode===9){var v=this.value,s=this.selectionStart,e=this.selectionEnd;this.value=v.substring(0, s)+' '+v.substring(e);this.selectionStart=this.selectionEnd=s+1;return false;}"><?=$sanitizedGET["txt$i"];?></textarea>
<tr><td colspan="2" style="text-align: center; border-top: 0pt">
<fieldset><legend><b><i>Type of rule:</i></b></legend>
    <input type="radio" name="typ<?=$i;?>" value="And" id="And<?=$i;?>"<?=[" checked",""][array("And"=>0,"Or"=>1)[$sanitizedGET["typ$i"]]];?>> <label for="And<?=$i;?>">Universal</label><br>
    <input type="radio" name="typ<?=$i;?>" value="Or" id="Or<?=$i;?>"<?=[""," checked"][array("And"=>0,"Or"=>1)[$sanitizedGET["typ$i"]]];?>> <label for="Or<?=$i;?>">Existential</label>
</fieldset>
</form>

<?php
}

if (!isset($sanitizedGET["sub"]) or ($sanitizedGET["sub"] == "+")) {
?>
<tr><td colspan="2" style="text-align: center; border-bottom: 0pt">
        <?php if (!isset($_GET["txt1"])) { ?>$$\textbf{not}\left(()\vdash a\right)$$<?php } ?>
        Name: <input type="text" name="nam<?=$n;?>"<?php if (!isset($_GET["txt1"])) { ?> value="Non-triviality"<?php } ?>>
<tr><td style="width: 20%; text-align: center; border-top: 0pt; border-right: 0pt; border-bottom: 0pt">
        <textarea name="var<?=$n;?>" style="width: 100%; height: 100pt"><?php if (!isset($_GET["txt1"])) { ?>a<?php } ?></textarea>
    <td style="width: 80%; text-align: center; border-top: 0pt; border-left: 0pt; border-bottom: 0pt">
        <textarea style="width: 100%; height: 100pt" name="txt<?=$n;?>" onkeydown="if(event.keyCode===9){var v=this.value,s=this.selectionStart,e=this.selectionEnd;this.value=v.substring(0, s)+' '+v.substring(e);this.selectionStart=this.selectionEnd=s+1;return false;}"><?php if (!isset($_GET["txt1"])) { ?>Not(Entails(Neutral,a))<?php } ?></textarea>
<tr><td colspan="2" style="text-align: center; border-top: 0pt">
<fieldset><legend><b><i>Type of rule:</i></b></legend>
    <input type="radio" name="typ<?=$n;?>" value="And" id="And<?=$n;?>"<?php if (isset($_GET["txt1"])) { ?> checked<?php } ?>> <label for="And<?=$n;?>">Universal</label><br>
    <input type="radio" name="typ<?=$n;?>" value="Or" id="Or<?=$n;?>"<?php if (!isset($_GET["txt1"])) { ?> checked<?php } ?>> <label for="Or<?=$n;?>">Existential</label>
</fieldset>
<?php
}
?>

</form>

<tr>
<td colspan="2" align="center">
<input type="checkbox" name="vsp"<?php
if (isset($_GET["vsp"])) {
    if ($_GET["vsp"]) print " checked";
}
?> id="vsp"> <label for="vsp">Toggle variable sharing?<br><small>(<a href="https://dx.doi.org/10.1007/s10849-026-09454-2" target="_blank">Rozek &amp; Tedder, 2026</a>)</small></label><br>
<input name="sub" type="submit" value="+">&emsp;<input name="sub" type="submit" value="Submit">
</table>

<td valign="center" style="height: 90%; width: 50%" align="left">
<textarea readonly style="width:90%; height:90%">
from z3 import *
from itertools import product
from time import time

found = False
size = 1

while not found :
    
    start = time()
    
    V = list(range(size))
    inV = lambda x : And(x >= 0, x < size)
    
    Entails = Function("Entails", IntSort(), IntSort(), BoolSort())
    Neutral = Int("Neutral")
    Comma = Function("Comma", IntSort(), IntSort(), IntSort())
    C = Function("C", IntSort(), IntSort(), IntSort())
    N = Function("N", IntSort(), IntSort())
    K = Function("K", IntSort(), IntSort(), IntSort())
    A = Function("A", IntSort(), IntSort(), IntSort())
    <?php
    if (isset($_GET["vsp"]) and $_GET["vsp"]) {
    ?>isA = Function("isA", IntSort(), BoolSort()) # for VSP
    isB = Function("isB", IntSort(), BoolSort()) # for VSP
    <?php
    }
    ?>
    
    s = Solver()
    
    # Stability
    s.add(inV(Neutral))
    s.add(And([
        inV(N(p))
        for p in V
    ]))
    s.add(And([
        And (
            inV(Comma(p,q)),
            inV(C(p,q)),
            inV(K(p,q)),
            inV(A(p,q))
        )
        for p, q in product(*([V]*2))
    ]))
    
    <?php
    if (isset($_GET["vsp"]) and $_GET["vsp"]) {
    ?># VSP
    for a in V:
        s.add(Implies(isA(a), isA(N(a))))
        s.add(Implies(isB(a), isB(N(a))))
        for b in V:
            s.add(Implies(And(isA(a), isA(b)), isA(C(a, b))))
            s.add(Implies(And(isA(a), isA(b)), isA(K(a, b))))
            s.add(Implies(And(isA(a), isA(b)), isA(A(a, b))))
            s.add(Implies(And(isB(a), isB(b)), isB(C(a, b))))
            s.add(Implies(And(isB(a), isB(b)), isB(K(a, b))))
            s.add(Implies(And(isB(a), isB(b)), isB(A(a, b))))
    
    s.add(Or([isA(i) != isB(i) for i in V]))
    s.add(And([Not(And(isA(i), isB(i))) for i in V]))
    s.add(And([
        Implies(
            And(isA(a), isB(b)),
            Not(Entails(Neutral, C(a, b)))
        )
        for a, b in product(*([V]*2))
    ]))
    s.add(Or([isA(i) for i in V]))
    s.add(Or([isB(i) for i in V]))
    
    <?php
    }
    ?># For ordering's sake
    
    s.add(And([ # Undesignated values before designated ones
            Implies(
                And(
                    Not(Entails(Neutral, a)),
                    Entails(Neutral, b)
                ),
                a < b
            )
            for a, b in product(*([V]*2))
        ]))
    s.add(And([ # Gaps after "false" values
            Implies(
                And(
                    Entails(Neutral, N(a)),
                    Not(Entails(Neutral, N(b))),
                    Entails(Neutral, a),
                    Entails(Neutral, b),
                ),
                a < b
            )
            for a, b in product(*([V]*2))
        ]))
    s.add(And([ # Gluts before "true" values
            Implies(
                And(
                    Entails(Neutral, N(a)),
                    Not(Entails(Neutral, N(b))),
                    Not(Entails(Neutral, a)),
                    Not(Entails(Neutral, b)),
                ),
                a < b
            )
            for a, b in product(*([V]*2))
        ]))
    s.add(And([ # Boths and Neithers in the middle :3
            Implies(
                And(
                    a == N(a),
                    b != N(b),
                    Entails(Neutral,a) == Entails(Neutral,b)
                ),
                And(
                    Implies(
                        Not(Entails(Neutral,a)),
                        b <= a
                    ),
                    Implies(
                        Entails(Neutral,a),
                        a <= b
                    )
                )
            )
            for a, b in product(*([V]*2))
        ]))
    s.add(And([ # Neutral = inf(V^+) under integer ordering
            Implies(
                Entails(Neutral, a),
                Neutral <= a
            )
            for a in V
        ]))
    
    # Rules
    
    <?php
    for ($i = 1; $i < $n; $i++) {
    ?>s.add(<?=$sanitizedGET["typ$i"];?>([
            <?=str_replace("\n","\n            ",$sanitizedGET["txt$i"]);?>
            <?php
            $arr = explode("\n", str_replace("\r","",$sanitizedGET["var$i"]));
            if (str_replace(["\r","\n",""],'',$sanitizedGET["var$i"]) != '') {
            ?>
            
            for <?=implode(', ', $arr);?> in <?php
                if (count($arr) == 1) {
                    print "V";
                } else {
                    print "product(*([V]*".count($arr)."))";
                }
            }
            ?>
            
        ]))
    
    <?php
    }
    ?>
    
    # Solving and display
    
    print(f"Solving at size {size}...")
    
    if s.check() == sat :
        
        end = time()
        
        print(f"SAT at size {size}")
        print(f"Solved in {end-start} seconds")
        m = s.model()
    
        print("\ninf(V^+) under integer ordering: ", m.eval(Neutral))
    
        print("\nEntailment:")
        print('⊢','|',*[a for a in V],sep='\t')
        print('----','+---',*['----' for a in V],sep='\t')
        for a in V :
            print(a,'|',*[m.eval(Entails(a,b)) for b in V],sep="\t")
    
        print("\nComma:")
        print(',','|',*[a for a in V],sep='\t')
        print('----','+---',*['----' for a in V],sep='\t')
        for a in V :
            print(a,'|',*[m.eval(Comma(a,b)) for b in V],sep="\t")
        
        print("\nNegation:")
        for a in V :
            print(f"~{a}",'|',m.eval(N(a)),sep="\t")
        
        print("\nImplication:")
        print('→','|',*[a for a in V],sep='\t')
        print('----','+---',*['----' for a in V],sep='\t')
        for a in V :
            print(a,'|',*[m.eval(C(a,b)) for b in V],sep="\t")
        
        print("\nConjunction:")
        print('&','|',*[a for a in V],sep='\t')
        print('----','+---',*['----' for a in V],sep='\t')
        for a in V :
            print(a,'|',*[m.eval(K(a,b)) for b in V],sep="\t")
        
        print("\nDisjunction:")
        print('∨','|',*[a for a in V],sep='\t')
        print('----','+---',*['----' for a in V],sep='\t')
        for a in V :
            print(a,'|',*[m.eval(A(a,b)) for b in V],sep="\t")
                
        print("\nMVLPG link")
        
        print(f"https://alexandrae.fr/mvlpg.php?n={size}", end='')
        for i in range(size) :
            if m.eval(Entails(Neutral, i)) :
                print(f"&v{i}={i}&d{i}=y",end='')
            else :
                print(f"&v{i}={i}&d{i}=n",end='')
        
        for a in V :
            print(f"&N{a}=", m.eval(N(a)), sep='', end='')
        
        for a in V :
            for b in V :
                print(f"&K{a};{b}=", m.eval(K(a,b)), sep='', end='')
    
        for a in V :
            for b in V :
                print(f"&A{a};{b}=", m.eval(A(a,b)), sep='', end='')
    
        for a in V :
            for b in V :
                print(f"&C{a};{b}=", m.eval(C(a,b)), sep='', end='')
    
        found = True
    
    else :
        
        end = time()
        
        print(f"UNSAT at size {size}")
        print(f"Solved in {end-start} seconds\n")
        
        size += 1
</textarea>
