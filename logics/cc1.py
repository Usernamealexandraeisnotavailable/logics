class CC1 :
        def __init__ (
            self : "CC1",
            value : int
        ) -> type(None) :
                if not value in [1,2,3,4] :
                        raise ValueError(f"Value needs to be 1, 2, 3 or 4, {value} given.")
                self.value = value
        
        # technical bs
        def __eq__ (
            self : "CC1",
            other : "CC1"
        ) -> bool :
                return self.value == other.value
        def __str__ (
            self : "CC1"
        ) -> str :
                return f"{self.value}"
        def __repr__ (
            self : "CC1"
        ) -> str :
                return f"{self.value}"
        
        # McCall, 1966, p. 418 :
        def __and__ (
            self : "CC1",
            other : "CC1"
        ) -> "CC1" :
                # a & b for a and b
                matrice_K = [
                    [1,2,3,4],
                    [1,2,4,3],
                    [3,3,3,4],
                    [4,3,4,3]
                ]
                return CC1(matrice_K[self.value-1][other.value-1])
        def __rshift__ (
            self : "CC1",
            other : "CC1"
        ) -> "CC1" :
                # a >> b for a implies b
                matrice_C = [
                    [1,4,3,4],
                    [4,1,4,3],
                    [1,4,1,4],
                    [4,1,4,1]
                ]
                return CC1(matrice_C[self.value-1][other.value-1])
        def __neg__ (
            self : "CC1"
        ) -> "CC1" :
                return CC1(5-self.value)
        def __or__ (
            self : "CC1",
            other : "CC1"
        ) -> "CC1" :
                return -((-self) & (-other))
