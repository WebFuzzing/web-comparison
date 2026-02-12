import math
import numpy
from collections import defaultdict
from typing import Optional
from dijkstar import Graph, find_path

from models.webstate import Webstate
from models.action import Action

Transition = tuple[Webstate, Action, Webstate]


class QLearning:
    DISCOUNT_FACTOR = 0.95
    GUMBEL_SOFTMAX_TAU = 1
    GUMBEL_SOFTMAX_IID = 1

    def __init__(self) -> None:
        self._graph = Graph()
        self.visit_count_table: dict[Transition, int] = defaultdict(lambda: 1, {})
        # track count of (s`, a, s) each time action a taken + update key if new state found
        self.state_action_count: dict[tuple[Webstate, Action], int] = defaultdict(lambda: 1, {})
        self.q_table: dict[tuple[Webstate, Action], float] = defaultdict(lambda: 1, {})
        self.transition_table: dict[tuple[Webstate, Action], Optional[Webstate]] = defaultdict(lambda: None, {})


    def visit_state(self, from_state: Webstate, action: Action, to_state: Webstate) -> None:
        visit_tuple: Transition = (from_state, action, to_state)
        self.transition_table[(from_state, action)] = to_state
        self.visit_count_table[visit_tuple] = self.visit_count_table[visit_tuple] + 1
        self.state_action_count[(from_state, action)] = self.state_action_count[(from_state, action)] + 1

        self._update_transition_function(visit_tuple)
     
    def update_q_function(self, from_state: Webstate, action: Action, only_curiosity: bool) -> float:
        curiosity = self.calculate_curiosity(from_state, action)
        to_state: Webstate = self.transition_table[(from_state, action)]

        decendent_q_value = 0
        if not only_curiosity:
            for a in to_state.valid_actions if to_state is not None else []:  # rewarding only if new state discovered for the first time
                decendent_q_value = max(decendent_q_value,
                                        self.q_table[(to_state, a)])

        self.q_table[(from_state, action)] = curiosity + (self.DISCOUNT_FACTOR * decendent_q_value)

        return self.q_table[(from_state, action)]

    def _get_count(self, from_state: Webstate, action: Action, to_state: Webstate = None) -> int:
        return self.state_action_count[(from_state, action)] if to_state == None else self.visit_count_table[
            (from_state, action, to_state)]

    def calculate_curiosity(self, from_state: Webstate, action: Action, to_state: Webstate = None) -> float:
        return 1.0 / math.sqrt(self._get_count(from_state, action, to_state))

    def calculate_action_weight(self, state: Webstate, action: Action) -> float:
        g_iids = dict()
        for a in state.valid_actions:
            g_iids[a] = -math.log(-math.log(numpy.random.uniform(0.0, 1.0)))

        def __calc(s, a):
            return math.exp((self.q_table[(s, a)] + g_iids[a]) / self.GUMBEL_SOFTMAX_TAU)

        return __calc(state, action) / sum(map(lambda a: __calc(state, a), state.valid_actions))


    def _update_transition_function(self, visit_tuple: Transition) -> None:
        self._graph.add_edge(visit_tuple[0], visit_tuple[2],
                             visit_tuple)  # to match with table from qlearning (s, a, s`)

    def generate_most_curious_action_trace(self, root_state: Webstate) -> list[Transition]:
        visit_tuple: Transition = min(self.visit_count_table, key=self.visit_count_table.get)

        shortest_path_edges: list[Transition] = find_path(self._graph, root_state, visit_tuple[2],
                                                          cost_func=lambda a, b, c, d: 1).edges
        return shortest_path_edges


