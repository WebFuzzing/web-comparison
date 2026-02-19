package generator.mutations;

import parsers.model.files.graph.elements.Call;
import parsers.model.files.graph.elements.Interaction;
import utils.RandomUtil;

import java.util.List;

/**
 * @author raphael
 */
public class CallMutation implements ICallMutation {


    /**
     * Convert a click in a double click
     *
     * @param call
     */
    @Override
    public void doubleClick(Call call) {
        call.setTypeOfCall("double_click");
    }

    /**
     * Set the type of interaction a double click in a menu interaction
     *
     * @param steps
     * @return
     */
    @Override
    public List<Interaction> randomDoubleClickMenu(List<Interaction> steps) {
        int mutation = RandomUtil.randomNumber(0, steps.size() - 1);

        steps.get(mutation).setType("double_click");

        return steps;
    }


}
