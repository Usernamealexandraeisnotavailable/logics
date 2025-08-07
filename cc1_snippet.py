from logics.cc1 import CC1
print("p;q;Kpq;Cpq;Apq;Np;Nq")
for i in range(1,5) :
    for j in range(1,5) :
        p = CC1(i)
        q = CC1(j)
        print(';'.join([
            str(_)
            for _ in [
                p,
                q,
                p&q,  # Kpq
                p>>q, # Cpq
                p|q,  # Apq
                -p,   # Np
                -q    # Nq
            ]
        ]))
