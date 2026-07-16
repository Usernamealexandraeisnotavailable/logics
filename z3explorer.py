from z3 import *
from itertools import product

found = False
size = 6

while not found :
    
    K = list(range(size))
    inK = lambda x : And(x >= 0, x < size)
    
    le = Function("le", IntSort(), IntSort(), BoolSort())
    mul = Function("mul", IntSort(), IntSort(), IntSort())
    imp = Function("imp", IntSort(), IntSort(), IntSort())
    neg = Function("neg", IntSort(), IntSort())
    meet = Function("meet", IntSort(), IntSort(), IntSort())
    join = Function("join", IntSort(), IntSort(), IntSort())
    empty = Int("empty")
    isA = Function("isA", IntSort(), BoolSort()) # for VSP
    isB = Function("isB", IntSort(), BoolSort()) # for VSP
    s = Solver()
    
    # VSP
    
    for a in K:
        s.add(Implies(isA(a), isA(neg(a))))
        s.add(Implies(isB(a), isB(neg(a))))
        for b in K:
            s.add(Implies(And(isA(a), isA(b)), isA(imp(a, b))))
            s.add(Implies(And(isA(a), isA(b)), isA(meet(a, b))))
            s.add(Implies(And(isA(a), isA(b)), isA(join(a, b))))
            s.add(Implies(And(isB(a), isB(b)), isB(imp(a, b))))
            s.add(Implies(And(isB(a), isB(b)), isB(meet(a, b))))
            s.add(Implies(And(isB(a), isB(b)), isB(join(a, b))))
    
    s.add(Or([isA(i) != isB(i) for i in K]))
    s.add(And([Not(And(isA(i), isB(i))) for i in K]))
    s.add(And([
        Implies(
            And(isA(a), isB(b)),
            Not(le(empty, imp(a, b)))
        )
        for a, b in product(K, repeat=2)
    ]))
    s.add(Or([isA(i) for i in K]))
    s.add(Or([isB(i) for i in K]))
    
    s.add(inK(empty))
    for a in K :
        s.add(inK(neg(a)))
        for b in K :
            s.add(inK(mul(a,b)))
            s.add(inK(imp(a,b)))
            s.add(inK(meet(a,b)))
            s.add(inK(join(a,b)))
    
    # for ordering's sake
    ubc = lambda x : Sum([If(le(x, y), 1, 0) for y in K])
    s.add(And([
            Implies(
                And(
                    ubc(a) > ubc(b),
                    le(empty, a) == le(empty, b)
                ),
                a < b
            )
            for a, b in product(K, repeat=2)
        ]))
    s.add(And([
            Implies(
                And(
                    Not(le(empty, a)),
                    le(empty, b)
                ),
                a < b
            )
            for a, b in product(K, repeat=2)
        ]))
    s.add(And([
            Implies(
                le(empty, a),
                empty <= a
            )
            for a in K
        ]))
    
    # rules
    rules = {
        "consistent" : [
            Implies(le(empty,a), Not(le(empty,neg(a))))
            for a in K
        ],
        "negtriviality" : [
            le(empty, neg(a))
            for a in K
        ],
        "triviality" : [
            le(empty, a)
            for a in K
        ],
        "identity" : [
            le(a,a)
            for a in K
        ],
        "cut" : [
            Implies(
                And(
                    le(gamma,a),
                    le(mul(delta,a),b)
                ),
                le(mul(gamma,delta),b)
            )
            for gamma, delta, a, b in product(K, repeat=4)
        ],
        "associativity" : [
            le(mul(gamma,mul(delta,epsilon)),a)
            ==
            le(mul(mul(gamma,delta),epsilon),a)
            for gamma, delta, epsilon, a in product(K, repeat=4)
        ],
        "pseudoneutrality" : [
            And(
                mul(empty,gamma) == gamma,
                mul(gamma,empty) == gamma
            )
            for gamma in K
        ],
        "permutation" : [
            Implies(
                le(mul(gamma,delta),a),
                le(mul(delta,gamma),a)
            )
            for gamma, delta, a in product(K, repeat=3)
        ],
        "contraction" : [
            Implies(
                le(mul(gamma,mul(a,a)),b),
                le(mul(gamma,a),b)
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "weakening" : [
            Implies(
                le(gamma,b),
                le(mul(gamma,a),b)
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "conditional proof" : [
            Implies(
                le(mul(gamma,a),b),
                le(gamma,imp(a,b))
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "modus ponens" : [
            Implies(
                And(
                    le(gamma,imp(a,b)),
                    le(delta,a)
                ),
                le(mul(gamma,delta),b)
            )
            for gamma, delta, a, b in product(K, repeat=4)
        ],
        "proof of negation" : [
            Implies(
                And(
                    le(mul(gamma,a),neg(b)),
                    le(mul(delta,a),b),
                ),
                le(mul(gamma,delta),neg(a))
            )
            for gamma, delta, a, b in product(K, repeat=4)
        ],
        "modus tollens" : [
            Implies(
                And(
                    le(gamma,neg(b)),
                    le(mul(delta,a),b),
                ),
                le(mul(gamma,delta),neg(a))
            )
            for gamma, delta, a, b in product(K, repeat=4)
        ],
        "conjunction" : [
            Implies(
                And(
                    le(gamma, a),
                    le(gamma, b)
                ),
                le(gamma, meet(a, b))
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "simplification" : [
            Implies(
                le(gamma, meet(a, b)),
                And(
                    le(gamma, a),
                    le(gamma, b)
                )
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "addition" : [
            Implies(
                Or(
                    le(gamma, a),
                    le(gamma, b)
                ),
                le(gamma, join(a, b))
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "cases" : [
            Implies(
                And(
                    le(gamma, join(a, b)),
                    le(mul(delta,a), c),
                    le(mul(delta,b), c)
                ),
                le(mul(gamma, delta), c)
            )
            for gamma, delta, a, b, c in product(K, repeat=5)
        ],
        "distrib" : [
            le(empty, imp(meet(a,join(b,c)),join(meet(a,b),meet(a,c))))
            for a, b, c in product(K, repeat=3)
        ],
        # connexive negation stuff
        "dni" : [
            Implies(
                le(gamma,a),
                le(gamma,neg(neg(a)))
            )
            for gamma, a in product(K, repeat=2)
        ],
        "dne" : [
            Implies(
                le(gamma,neg(neg(a))),
                le(gamma,a)
            )
            for gamma, a in product(K, repeat=2)
        ],
        "anticonditional proof" : [
            Implies(
                le(mul(gamma,a),neg(b)),
                le(gamma,neg(imp(a,b)))
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "ponens modus" : [
            Implies(
                And(
                    le(gamma,neg(imp(a,b))),
                    le(delta,a)
                ),
                le(mul(gamma,delta),neg(b))
            )
            for gamma, delta, a, b in product(K, repeat=4)
        ],
        "antidisjunction" : [
            Implies(
                And(
                    le(gamma, neg(a)),
                    le(gamma, neg(b))
                ),
                le(gamma, neg(join(a, b)))
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "antiaddition" : [
            Implies(
                le(gamma, neg(join(a, b))),
                And(
                    le(gamma, neg(a)),
                    le(gamma, neg(b))
                )
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "antisimplification" : [
            Implies(
                Or(
                    le(gamma, neg(a)),
                    le(gamma, neg(b))
                ),
                le(gamma, neg(meet(a, b)))
            )
            for gamma, a, b in product(K, repeat=3)
        ],
        "anticonjunction" : [
            Implies(
                And(
                    le(gamma, neg(meet(a, b))),
                    le(mul(delta,neg(a)), c),
                    le(mul(delta,neg(b)), c)
                ),
                le(mul(gamma, delta), c)
            )
            for gamma, delta, a, b, c in product(K, repeat=5)
        ],
        "reciprocal boethius rule" : [
            le(empty,imp(neg(imp(a,b)),imp(a,neg(b))))
            for a, b in product(K, repeat=2)
        ],
        "aristotle's theses" : [
            And(
                le(empty,neg(imp(a,neg(a)))),
                le(empty,neg(imp(neg(a),a)))
            )
            for a in K
        ],
        "boethius's theses" : [
            And(
                le(empty,imp(imp(a,b),neg(imp(a,neg(b))))),
                le(empty,imp(imp(a,neg(b)),neg(imp(a,b))))
            )
            for a, b in product(K, repeat=2)
        ],
        "currying" : [
            Implies(
                le(gamma,imp(meet(a,b),c)),
                le(gamma,imp(a,imp(b,c)))
            )
            for gamma, a, b, c in product(K, repeat=4)
        ],
        "uncurrying" : [
            Implies(
                le(gamma,imp(a,imp(b,c))),
                le(gamma,imp(meet(a,b),c))
            )
            for gamma, a, b, c in product(K, repeat=4)
        ],
        "lem" : [
            le(empty,join(a,neg(a)))
            for a in K
        ],
    }
    def should_we_satisfy (rule, maybe) :
        global s
        global rules
        match maybe :
            case 1 : # yes 
                s.add(And(rules[rule]))
                return None
            case 0 : # no
                s.add(Or([Not(_) for _ in rules[rule]]))
                return None
    
    # rule switches
    
    should_we_satisfy("consistent", .5)
    # should_we_satisfy("negtriviality", 0)
    should_we_satisfy("triviality", 0)
    should_we_satisfy("identity", 1)
    should_we_satisfy("cut", 1)
    should_we_satisfy("associativity", 1)
    should_we_satisfy("pseudoneutrality", 1)
    should_we_satisfy("permutation", 1)
    should_we_satisfy("contraction", 1)
    should_we_satisfy("weakening", 0)
    
    should_we_satisfy("conditional proof", 1)
    should_we_satisfy("modus ponens", 1)
    should_we_satisfy("conjunction", 1)
    should_we_satisfy("cases", 1)
    should_we_satisfy("addition", 1)
    should_we_satisfy("simplification", 1)
    # should_we_satisfy("proof of negation", 1)
    # should_we_satisfy("modus tollens", 1)
    should_we_satisfy("distrib", 1)
    should_we_satisfy("dni", 1)
    should_we_satisfy("dne", 1)
    # connexive
    # should_we_satisfy("aristotle's theses", 1)
    # should_we_satisfy("boethius's theses", 1)
    should_we_satisfy("anticonditional proof", 1)
    # should_we_satisfy("ponens modus", 1)
    should_we_satisfy("antiaddition", 1)
    should_we_satisfy("antisimplification", 1)
    should_we_satisfy("antidisjunction", 1)
    should_we_satisfy("anticonjunction", 1)
    # s.add(And([le(empty, neg(meet(imp(a,b),imp(neg(a),b)))) for a in K for b in K])) # abelard
    # s.add(And([le(empty, neg(meet(imp(a,b),imp(a,neg(b))))) for a in K for b in K])) # abelard
    
    # s.add(Or([Not(le(empty, imp(a,neg(a)))) for a in K]))
    # s.add(Or([Not(le(empty, imp(neg(a),a))) for a in K]))
    """
    s.add(Or([neg(a) != a for a in K]))
    s.add(Or([Not(le(empty, imp(neg(a),a))) for a in K]))
    s.add(Or([Not(le(empty, neg(imp(a,a)))) for a in K]))
    s.add(And(neg(2) == 2, neg(3) == 3))
    """
    
    print(f"Solving at size {size}...")
    
    if s.check() == sat :
        print(f"SAT at size {size}")
        m = s.model()
    
        print("\nempty =", m.eval(empty))
    
        print("\n≤")
        for a in K :
            for b in K :
                print(f"{a}≤{b} =", m.eval(le(a,b)))
        
        print("\nmul")
        for a in K :
            for b in K :
                print(f"{a}*{b} =", m.eval(mul(a,b)))
                
        print("\nMVLPG link")
        
        print(f"https://alexandrae.fr/mvlpg.php?n={size}", end='')
        for i in range(size) :
            if m.eval(le(empty, i)) :
                print(f"&v{i}={i}&d{i}=y",end='')
            else :
                print(f"&v{i}={i}&d{i}=n",end='')
        
        for a in K :
            print(f"&N{a}=", m.eval(neg(a)), sep='', end='')
        
        for a in K :
            for b in K :
                print(f"&K{a};{b}=", m.eval(meet(a,b)), sep='', end='')
    
        for a in K :
            for b in K :
                print(f"&A{a};{b}=", m.eval(join(a,b)), sep='', end='')
    
        for a in K :
            for b in K :
                print(f"&C{a};{b}=", m.eval(imp(a,b)), sep='', end='')
    
        found = True
    
    else :
        print(f"UNSAT at size {size}")
        size += 1
