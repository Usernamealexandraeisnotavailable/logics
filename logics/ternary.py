import sys

class ternary :
        def __init__ (
            self : "ternary",
            argument : "bool|NoneType"
        ) -> "NoneType" :
                if not (isinstance(argument, bool) or argument is None) :
                        raise TypeError(", ".join([
                            f"Initialization arguments need to be of type {bool}|{type(None)}",
                            f"type {type(argument)} given."
                        ]))
                self.value = [-1,1][int(argument)] if isinstance(argument, bool) else 0
        def __repr__ (
            self : "ternary"
        ) -> "str" :
                return f"{self.value}"
        def __int__ (
            self : "ternary"
        ) -> "int" :
                return self.value
        def __str__ (
            self : "ternary"
        ) -> "str" :
                return f"{self.value}"
        def __le__ (
            self : "ternary",
            other : "ternary"
        ) -> "bool" :
                return self.value <= other.value
        def __lt__ (
            self : "ternary",
            other : "ternary"
        ) -> "bool" :
                return self.value < other.value
        def __eq__ (
            self : "ternary",
            other : "ternary"
        ) -> "bool" :
                return self.value == other.value
        def __and__ (
            self : "ternary",
            other : "ternary"
        ) -> "ternary" :
                if self.value <= other.value :
                        return self
                return other
        def __or__ (
            self : "ternary",
            other : "ternary"
        ) -> "ternary" :
                if self.value >= other.value :
                        return self
                return other
        def __neg__ (
            self : "ternary"
        ) -> "ternary" :
                return [
                    ternary.true,
                    ternary.zero,
                    ternary.false
                ][self.value+1]
ternary.false = ternary(False)
ternary.zero = ternary(None)
ternary.true = ternary(True)

class model :
        def __init__ (
            self : "model",
            data_type : "type" = ternary,
            **kwargs : "dict"
        ) -> "NoneType" :
                for key in kwargs :
                        if not isinstance(kwargs[key], data_type) :
                                raise TypeError(", ".join([
                                    f"kwargs elements should all have type {data_type}",
                                    f"type {type(kwargs[key])} given at key \"{key}\"."
                                ]))
                self.context = kwargs
                self.data_type = data_type
        def __str__ (
            self : "model"
        ) -> "str" :
                return f"{self.context}"
        def __repr__ (
            self : "model"
        ) -> "str" :
                return f"model(**{self.context})"
        def __eq__ (
            self : "model",
            other : "model"
        ) -> "bool" :
                return (self.context == other.context) and (self.data_type == other.data_type)
        def atom_completions (
            self : "model"
        ) -> "data_type" :
                first_key = list(self.context)[0]
                sub = {}
                for key in self.context :
                        if key != first_key :
                                sub[key] = self.context[key]
                if self.context[first_key] == self.data_type.zero :
                        if sub == {} :
                                return [
                                    model(**{first_key: self.data_type.true}),
                                    model(**{first_key: self.data_type.false})
                                ]
                        ref = model(**sub).atom_completions()
                        res = []
                        for subcontext in ref :
                                for value in [
                                    self.data_type.true,
                                    self.data_type.false
                                ] :
                                        res.append({first_key: value})
                                        res[-1].update(subcontext.context)
                                        res[-1] = model(**res[-1])
                        return res
                if sub == {} :
                        return [model(**{first_key: self.context[first_key]})]
                ref = model(**sub).atom_completions()
                res = []
                for subcontext in ref :
                        res.append({first_key: self.context[first_key]})
                        res[-1].update(subcontext.context)
                        res[-1] = model(**res[-1])
                return res

class proposition :
        def __init__ (
            self : "proposition",
            *expression : "tuple"
        ) -> "NoneType" :
                self.expression = expression
                self.data_type = type(self)
        def __str__ (
            self : "proposition"
        ) -> "str" :
                return f"{self.expression}"
        def __repr__ (
            self : "proposition"
        ) -> "str" :
                return f"{self.expression}"
        def __eq__ (
            self : "model",
            other : "model"
        ) -> "bool" :
                return (self.expression == other.expression) and (self.data_type == other.data_type)
        def valuation (
            self : "proposition",
            context : "model",
        ) -> "ternary" :
                def to_tuple (
                    data : "any"
                ) -> "tuple" :
                        if isinstance(data, str) :
                                return (data,)
                        return data
                if len(self.expression) == 2 and isinstance(self.expression, tuple) :
                        match self.expression[0] :
                                case "and" :
                                        return self.valuation_and (
                                            context = context,
                                            a = self.data_type(*to_tuple(self.expression[1][0])),
                                            b = self.data_type(*to_tuple(self.expression[1][1]))
                                        )
                                case "or" :
                                        return self.valuation_or (
                                            context = context,
                                            a = self.data_type(*to_tuple(self.expression[1][0])),
                                            b = self.data_type(*to_tuple(self.expression[1][1]))
                                        )
                                case "implies" :
                                        return self.valuation_implies (
                                            context = context,
                                            a = self.data_type(*to_tuple(self.expression[1][0])),
                                            b = self.data_type(*to_tuple(self.expression[1][1]))
                                        )
                                case "not" :
                                        operand = self.expression[1]
                                        if isinstance(operand, tuple) and len(operand) == 1 :
                                            operand = operand[0]
                                        return self.valuation_not (
                                            context = context,
                                            a = self.data_type(*to_tuple(operand))
                                        )
                                case _ :
                                        raise ValueError(f"Operator must be 'and', 'or', 'implies' or 'not', '{self.expression[0]}' given.")
                return context.context[self.expression[0]]
        def submodels (
            self : "proposition",
            context : "model"
        ) -> "ternary" :
                submods = context.atom_completions()
                output = []
                for submod in submods :
                        if self.valuation(submod) == ternary.true :
                                output.append(submod)
                return output
        def valuation_and (
            self : "proposition",
            context : "model",
            a : "proposition",
            b : "proposition"
        ) -> "ternary" :
                inter = [mod for mod in a.submodels(context) if mod in b.submodels(context)]
                if len(inter) == 0 :
                        return ternary.false
                subs = context.atom_completions()
                value = a.valuation(subs[0]) & b.valuation(subs[0])
                for mod in subs[1:] :
                        if value != a.valuation(mod) & b.valuation(mod) :
                                return ternary.zero
                return value
        def valuation_or (
            self : "proposition",
            context : "model",
            a : "proposition",
            b : "proposition"
        ) -> "ternary" :
                unn = []
                for mod in (a.submodels(context) + b.submodels(context)) :
                        if mod not in unn :
                                unn.append(mod)
                if len(unn) == 0 :
                        return ternary.false
                subs = context.atom_completions()
                value = a.valuation(subs[0]) | b.valuation(subs[0])
                for mod in subs[1:] :
                        if value != a.valuation(mod) | b.valuation(mod) :
                                return ternary.zero
                return value
        def valuation_not (
            self : "proposition",
            context : "model",
            a : "proposition"
        ) -> "ternary" :
                subs = a.submodels(context)
                if len(subs) == 0 :
                        return ternary.true
                subs = context.atom_completions()
                value = -a.valuation(subs[0])
                for mod in subs[1:] :
                        if value != -a.valuation(mod) :
                                return ternary.zero
                return value

class classical(proposition) :
        def valuation_implies (
            self : "classical",
            context : "model",
            a : "classical",
            b : "classical"
        ) -> "ternary" :
                subs = a.submodels(context)
                if len(subs) == 0 :
                        return ternary.true
                value = b.valuation(subs[0])
                for mod in subs[1:] :
                        if value != b.valuation(mod) :
                                return ternary.zero
                return value

class efns_connexive(proposition) :
        def valuation_implies (
            self : "efns_connexive",
            context : "model",
            a : "efns_connexive",
            b : "efns_connexive"
        ) -> "ternary" :
                subs = a.submodels(context)
                if len(subs) == 0 :
                        return ternary.false
                        # EFNS stands for "ex falso nihil sequitur"
                value = b.valuation(subs[0])
                for mod in subs[1:] :
                        if value != b.valuation(mod) :
                                return ternary.zero
                return value

class middle_ground_connexive(proposition) :
        def valuation_implies (
            self : "middle_ground_connexive",
            context : "model",
            a : "middle_ground_connexive",
            b : "middle_ground_connexive"
        ) -> "ternary" :
                subs = a.submodels(context)
                if len(subs) == 0 :
                        return ternary.zero
                value = b.valuation(subs[0])
                for mod in subs[1:] :
                        if value != b.valuation(mod) :
                                return ternary.zero
                return value