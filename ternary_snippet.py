from logics.ternary import *

con = model (
    truth = ternary.true,
    A = ternary.zero,
    B = ternary.zero,
    falsity = ternary.false
)
print(f"**MODEL :** {con}")
atoms = list(con.context)
print("# COMMON")
print("## Conjunction")
for atom1 in atoms :
        for atom2 in atoms :
                prop = proposition('and',(atom1,atom2))
                print(prop,"yields",prop.valuation(con))
print("## Disjunction")
for atom1 in atoms :
        for atom2 in atoms :
                prop = proposition('or',(atom1,atom2))
                print(prop,"yields",prop.valuation(con))
print("## Negation")
for atom1 in atoms :
        prop = proposition('not',atom1)
        print(prop,"yields",prop.valuation(con))
print("# CLASSICAL")
print("## Idempotency")
for atom1 in atoms :
        prop = classical('implies',(atom1,atom1))
        print(prop,"yields",prop.valuation(con))
print("## Law of excluded middle")
for atom1 in atoms :
        prop = classical('or',(atom1,('not',atom1)))
        print(prop,"yields",prop.valuation(con))
print("## Aristotle's theses")
for atom1 in atoms :
        prop = classical('not',(('implies',(atom1,('not',atom1))),))
        print(prop,"yields",prop.valuation(con))
        prop = classical('not',(('implies',(('not',atom1),atom1)),))
        print(prop,"yields",prop.valuation(con))
print("## Boethius' theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('not',(('implies',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print(prop,"yields",prop.valuation(con))
                prop = classical('not',(('implies',(('implies',(atom1,('not',atom2))),('implies',(atom1,atom2)))),))
                print(prop,"yields",prop.valuation(con))
print("## Abelard's theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('not',(('and',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print(prop,"yields",prop.valuation(con))
                prop = classical('not',(('and',(('implies',(atom1,atom2)),('implies',(('not',atom1),atom2)))),))
                print(prop,"yields",prop.valuation(con))
print("## Non-symmetric implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop1 = classical('implies',(('and',(atom1,atom2)),atom1))
                prop2 = classical('implies',(atom1,('and',(atom1,atom2))))
                if prop1.valuation(con) != prop2.valuation(con) :
                        print(prop1,"and",prop2,"yield",prop1.valuation(con),"and",prop2.valuation(con),"respectively")
print("## Propositionally internalized modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('implies',(('and',(('implies',(atom1,atom2)),atom1)),atom2))
                print(prop,"yields",prop.valuation(con))
print("## Actual modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                if classical('and',(('implies',(atom1,atom2)),atom1)).valuation(con) == ternary.true :
                        prop = classical(atom2)
                        print(classical('and',(('implies',(atom1,atom2)),atom1)),"yields",prop,"being",prop.valuation(con))
                else :
                        prop = classical('and',(('implies',(atom1,atom2)),atom1))
                        print('>',prop,"yields",prop.valuation(con),"so it's irrelevant")
print("## Propositionally internalized contraposition")
for atom1 in atoms :
        for atom2 in atoms :
                prop = classical('implies',(('and',(('implies',(atom1,atom2)),('not',atom2))),('not',atom1)))
                print(prop,"yields",prop.valuation(con))
print("# EFNS CONNEXIVE")
print("## Idempotency")
for atom1 in atoms :
        prop = efns_connexive('implies',(atom1,atom1))
        print(prop,"yields",prop.valuation(con))
print("## Law of excluded middle")
for atom1 in atoms :
        prop = efns_connexive('or',(atom1,('not',atom1)))
        print(prop,"yields",prop.valuation(con))
print("## Aristotle's theses")
for atom1 in atoms :
        prop = efns_connexive('not',(('implies',(atom1,('not',atom1))),))
        print(prop,"yields",prop.valuation(con))
        prop = efns_connexive('not',(('implies',(('not',atom1),atom1)),))
        print(prop,"yields",prop.valuation(con))
print("## Boethius' theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('not',(('implies',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print(prop,"yields",prop.valuation(con))
                prop = efns_connexive('not',(('implies',(('implies',(atom1,('not',atom2))),('implies',(atom1,atom2)))),))
                print(prop,"yields",prop.valuation(con))
print("## Abelard's theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('not',(('and',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print(prop,"yields",prop.valuation(con))
                prop = efns_connexive('not',(('and',(('implies',(atom1,atom2)),('implies',(('not',atom1),atom2)))),))
                print(prop,"yields",prop.valuation(con))
print("## Non-symmetric implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop1 = efns_connexive('implies',(('and',(atom1,atom2)),atom1))
                prop2 = efns_connexive('implies',(atom1,('and',(atom1,atom2))))
                if prop1.valuation(con) != prop2.valuation(con) :
                        print(prop1,"and",prop2,"yield",prop1.valuation(con),"and",prop2.valuation(con),"respectively")
print("## Propositionally internalized modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('implies',(('and',(('implies',(atom1,atom2)),atom1)),atom2))
                print(prop,"yields",prop.valuation(con))
print("## Actual modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                if efns_connexive('and',(('implies',(atom1,atom2)),atom1)).valuation(con) == ternary.true :
                        prop = efns_connexive(atom2)
                        print(efns_connexive('and',(('implies',(atom1,atom2)),atom1)),"yields",prop,"being",prop.valuation(con))
                else :
                        prop = efns_connexive('and',(('implies',(atom1,atom2)),atom1))
                        print('>',prop,"yields",prop.valuation(con),"so it's irrelevant")
print("## Propositionally internalized contraposition")
for atom1 in atoms :
        for atom2 in atoms :
                prop = efns_connexive('implies',(('and',(('implies',(atom1,atom2)),('not',atom2))),('not',atom1)))
                print(prop,"yields",prop.valuation(con))
print("# MIDDLE GROUND")
print("## Idempotency")
for atom1 in atoms :
        prop = middle_ground_connexive('implies',(atom1,atom1))
        print(prop,"yields",prop.valuation(con))
print("## Law of excluded middle")
for atom1 in atoms :
        prop = middle_ground_connexive('or',(atom1,('not',atom1)))
        print(prop,"yields",prop.valuation(con))
print("## Aristotle's theses")
for atom1 in atoms :
        prop = middle_ground_connexive('not',(('implies',(atom1,('not',atom1))),))
        print(prop,"yields",prop.valuation(con))
        prop = middle_ground_connexive('not',(('implies',(('not',atom1),atom1)),))
        print(prop,"yields",prop.valuation(con))
print("## Boethius' theses")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground_connexive('not',(('implies',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print(prop,"yields",prop.valuation(con))
                prop = middle_ground_connexive('not',(('implies',(('implies',(atom1,('not',atom2))),('implies',(atom1,atom2)))),))
                print(prop,"yields",prop.valuation(con))
print("## Abelard's thesis")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground_connexive('not',(('and',(('implies',(atom1,atom2)),('implies',(atom1,('not',atom2))))),))
                print(prop,"yields",prop.valuation(con))
                prop = middle_ground_connexive('not',(('and',(('implies',(atom1,atom2)),('implies',(('not',atom1),atom2)))),))
                print(prop,"yields",prop.valuation(con))
print("## Non-symmetric implication")
for atom1 in atoms :
        for atom2 in atoms :
                prop1 = middle_ground_connexive('implies',(('and',(atom1,atom2)),atom1))
                prop2 = middle_ground_connexive('implies',(atom1,('and',(atom1,atom2))))
                if prop1.valuation(con) != prop2.valuation(con) :
                        print(prop1,"and",prop2,"yield",prop1.valuation(con),"and",prop2.valuation(con),"respectively")
print("## Propositionally internalized modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground_connexive('implies',(('and',(('implies',(atom1,atom2)),atom1)),atom2))
                print(prop,"yields",prop.valuation(con))
print("## Actual modus ponens")
for atom1 in atoms :
        for atom2 in atoms :
                if middle_ground_connexive('and',(('implies',(atom1,atom2)),atom1)).valuation(con) == ternary.true :
                        prop = middle_ground_connexive(atom2)
                        print(middle_ground_connexive('and',(('implies',(atom1,atom2)),atom1)),"yields",prop,"being",prop.valuation(con))
                else :
                        prop = middle_ground_connexive('and',(('implies',(atom1,atom2)),atom1))
                        print('>',prop,"yields",prop.valuation(con),"so it's irrelevant")
print("## Propositionally internalized contraposition")
for atom1 in atoms :
        for atom2 in atoms :
                prop = middle_ground_connexive('implies',(('and',(('implies',(atom1,atom2)),('not',atom2))),('not',atom1)))
                print(prop,"yields",prop.valuation(con))
