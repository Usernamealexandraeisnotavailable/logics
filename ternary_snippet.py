from logics.ternary import *

mod = model (
    onePlusOneIsTwo = ternary.true,
    goldbachConjecture = ternary.zero,
    riemannHypothesis = ternary.zero,
    moonMadeOfCheese = ternary.false
)
print('\n',f"**MODEL :** {mod}")
atoms = list(mod.context)




print('\n',"# COMMON")
print('\n',"## Conjunction")
for atom1 in atoms :
        for atom2 in atoms :
                prop = proposition('and',(atom1,atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Disjunction")
for atom1 in atoms :
        for atom2 in atoms :
                prop = proposition('or',(atom1,atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Negation")
for atom1 in atoms :
        prop = proposition('not',atom1)
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Law of excluded middle")
for atom1 in atoms :
        prop = proposition('or',(atom1,('not',atom1)))
        print('\n',prop,"yields",prop.valuation(mod))




print('\n',"# CLASSICAL")
print('\n',"## Implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('implies',(atom1,atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Biconditional")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('and',(('implies',(atom1,atom2)),('implies',(atom2,atom1))))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Equivalence")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('or',(('and',(atom1,atom2)),('and',(('not',atom1),('not',atom2)))))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Idempotency")
for atom1 in atoms :
        prop = classical('implies',(atom1,atom1))
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Aristotle's theses")
for atom1 in atoms :
        prop = classical('not',(('implies',(atom1,('not',atom1))),))
        print('\n',prop,"yields",prop.valuation(mod))
        prop = classical('not',(('implies',(('not',atom1),atom1)),))
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Boethius' theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('not',(('implies',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print('\n',prop,"yields",prop.valuation(mod))
                prop = classical('not',(('implies',(('implies',(atom1,('not',atom2))),('implies',(atom1,atom2)))),))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Abelard's theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('not',(('and',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print('\n',prop,"yields",prop.valuation(mod))
                prop = classical('not',(('and',(('implies',(atom1,atom2)),('implies',(('not',atom1),atom2)))),))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Non-symmetric implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop1 = classical('implies',(('and',(atom1,atom2)),atom1))
                prop2 = classical('implies',(atom1,('and',(atom1,atom2))))
                if prop1.valuation(mod) != prop2.valuation(mod) :
                        print('\n',prop1,"and",prop2,"yield",prop1.valuation(mod),"and",prop2.valuation(mod),"respectively")
print('\n',"## Propositionally internalized modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('implies',(('and',(('implies',(atom1,atom2)),atom1)),atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Actual modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                if classical('and',(('implies',(atom1,atom2)),atom1)).valuation(mod) == ternary.true :
                        prop = classical(atom2)
                        print('\n',classical('and',(('implies',(atom1,atom2)),atom1)),"yields",prop,"being",prop.valuation(mod))
                else :
                        prop = classical('and',(('implies',(atom1,atom2)),atom1))
                        print('\n','>',prop,"yields",prop.valuation(mod),"so it's irrelevant")
print('\n',"## Propositionally internalized contraposition")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('implies',(('and',(('implies',(atom1,atom2)),('not',atom2))),('not',atom1)))
                print('\n',prop,"yields",prop.valuation(mod))




print('\n',"# EFNS CONNEXIVE")
print('\n',"## Implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('implies',(atom1,atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Biconditional")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('and',(('implies',(atom1,atom2)),('implies',(atom2,atom1))))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Equivalence")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('or',(('and',(atom1,atom2)),('and',(('not',atom1),('not',atom2)))))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Idempotency")
for atom1 in atoms :
        prop = efns_connexive('implies',(atom1,atom1))
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Aristotle's theses")
for atom1 in atoms :
        prop = efns_connexive('not',(('implies',(atom1,('not',atom1))),))
        print('\n',prop,"yields",prop.valuation(mod))
        prop = efns_connexive('not',(('implies',(('not',atom1),atom1)),))
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Boethius' theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('not',(('implies',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print('\n',prop,"yields",prop.valuation(mod))
                prop = efns_connexive('not',(('implies',(('implies',(atom1,('not',atom2))),('implies',(atom1,atom2)))),))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Abelard's theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('not',(('and',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print('\n',prop,"yields",prop.valuation(mod))
                prop = efns_connexive('not',(('and',(('implies',(atom1,atom2)),('implies',(('not',atom1),atom2)))),))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Non-symmetric implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop1 = efns_connexive('implies',(('and',(atom1,atom2)),atom1))
                prop2 = efns_connexive('implies',(atom1,('and',(atom1,atom2))))
                if prop1.valuation(mod) != prop2.valuation(mod) :
                        print('\n',prop1,"and",prop2,"yield",prop1.valuation(mod),"and",prop2.valuation(mod),"respectively")
print('\n',"## Propositionally internalized modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('implies',(('and',(('implies',(atom1,atom2)),atom1)),atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Actual modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                if efns_connexive('and',(('implies',(atom1,atom2)),atom1)).valuation(mod) == ternary.true :
                        prop = efns_connexive(atom2)
                        print('\n',efns_connexive('and',(('implies',(atom1,atom2)),atom1)),"yields",prop,"being",prop.valuation(mod))
                else :
                        prop = efns_connexive('and',(('implies',(atom1,atom2)),atom1))
                        print('\n','>',prop,"yields",prop.valuation(mod),"so it's irrelevant")
print('\n',"## Propositionally internalized contraposition")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('implies',(('and',(('implies',(atom1,atom2)),('not',atom2))),('not',atom1)))
                print('\n',prop,"yields",prop.valuation(mod))




print('\n',"# MIDDLE GROUND")
print('\n',"## Implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('implies',(atom1,atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Biconditional")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('and',(('implies',(atom1,atom2)),('implies',(atom2,atom1))))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Equivalence")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('or',(('and',(atom1,atom2)),('and',(('not',atom1),('not',atom2)))))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Idempotency")
for atom1 in atoms :
        prop = middle_ground('implies',(atom1,atom1))
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Aristotle's theses")
for atom1 in atoms :
        prop = middle_ground('not',(('implies',(atom1,('not',atom1))),))
        print('\n',prop,"yields",prop.valuation(mod))
        prop = middle_ground('not',(('implies',(('not',atom1),atom1)),))
        print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Boethius' theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('not',(('implies',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print('\n',prop,"yields",prop.valuation(mod))
                prop = middle_ground('not',(('implies',(('implies',(atom1,('not',atom2))),('implies',(atom1,atom2)))),))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Abelard's thesis")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('not',(('and',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print('\n',prop,"yields",prop.valuation(mod))
                prop = middle_ground('not',(('and',(('implies',(atom1,atom2)),('implies',(('not',atom1),atom2)))),))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Non-symmetric implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop1 = middle_ground('implies',(('and',(atom1,atom2)),atom1))
                prop2 = middle_ground('implies',(atom1,('and',(atom1,atom2))))
                if prop1.valuation(mod) != prop2.valuation(mod) :
                        print('\n',prop1,"and",prop2,"yield",prop1.valuation(mod),"and",prop2.valuation(mod),"respectively")
print('\n',"## Propositionally internalized modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('implies',(('and',(('implies',(atom1,atom2)),atom1)),atom2))
                print('\n',prop,"yields",prop.valuation(mod))
print('\n',"## Actual modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                if middle_ground('and',(('implies',(atom1,atom2)),atom1)).valuation(mod) == ternary.true :
                        prop = middle_ground(atom2)
                        print('\n',middle_ground('and',(('implies',(atom1,atom2)),atom1)),"yields",prop,"being",prop.valuation(mod))
                else :
                        prop = middle_ground('and',(('implies',(atom1,atom2)),atom1))
                        print('\n','>',prop,"yields",prop.valuation(mod),"so it's irrelevant")
print('\n',"## Propositionally internalized contraposition")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground('implies',(('and',(('implies',(atom1,atom2)),('not',atom2))),('not',atom1)))
                print('\n',prop,"yields",prop.valuation(mod))
