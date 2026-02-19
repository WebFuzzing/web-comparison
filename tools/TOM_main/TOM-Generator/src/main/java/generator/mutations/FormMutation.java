package generator.mutations;

import parsers.model.files.graph.elements.Action;
import utils.RandomUtil;

import java.util.ArrayList;
import java.util.List;

/**
 * @author raphael
 */
public class FormMutation implements IFormMutation {

    private List<Action> formActions;

    public FormMutation(List<Action> formActions) {
        this.formActions = new ArrayList<>();
        for (Action a : formActions) {
            this.formActions.add(new Action(a));
        }
    }

    /**
     * Change order of execution of actions
     *
     * @return List of mutated actions
     */
    @Override
    public List<Action> slip() {
        List<Action> mutatedActions = new ArrayList<>(this.formActions);

        int[] randoms = RandomUtil.generate2Nums(0, mutatedActions.size() - 1);
        int randomNum = randoms[0];
        int randomNum2 = randoms[1];

        Action action1 = mutatedActions.get(randomNum);
        Action action2;
        do {
            action2 = mutatedActions.get(randomNum2);
        } while (action1.myEquals(action2));

        //change the position of the actions
        mutatedActions.set(randomNum, action2);
        mutatedActions.set(randomNum2, action1);

        return mutatedActions;
    }

    /**
     * Change order of execution of a chosen action with another random action
     *
     * @param action
     * @return List of mutated actions
     */
    @Override
    public List<Action> slipAction(Action action) {

        List<Action> mutatedActions = new ArrayList<>(this.formActions);
        Action aux;

        int index;
        int random;

        index = mutatedActions.indexOf(action);

        do {
            random = RandomUtil.randomNumber(0, mutatedActions.size() - 1);
        } while (index == random);

        aux = mutatedActions.get(index);

        // SWAP the actions
        mutatedActions.set(index, mutatedActions.get(random));
        mutatedActions.set(random, aux);

        return mutatedActions;
    }

    /**
     * Delete a random action
     *
     * @return
     */
    @Override
    public List<Action> lapse() {
        List<Action> mutatedActions = new ArrayList<>(formActions);

        int randNum = RandomUtil.randomNumber(0, mutatedActions.size() - 1);
        mutatedActions.remove(randNum);

        return mutatedActions;
    }

    /**
     * Delete one action
     *
     * @param action
     * @return
     */
    @Override
    public List<Action> lapseAction(Action action) {
        List<Action> mutatedActions = new ArrayList<>();

        for (Action a : formActions) {
            if (!a.equals(action)) {
                mutatedActions.add(new Action(a));
            }
        }

        return mutatedActions;
    }

    /**
     * Introduce a Random value
     *
     * @param value
     * @param length
     * @param gonnaFail
     * @return
     */
    @Override
    public String mistake(String value, int length, int gonnaFail) {
        return RandomUtil.randomString(value, length);
    }

    /**
     * Delete a random required field
     *
     * @return
     */
    @Override
    public List<Action> removeRequiredInput() {
        List<Action> mutatedActions = copyActions();

        int i = mutatedActions.size() - 1;

        // Go through all the actions
        while (i >= 0) {
            String type = mutatedActions.get(i).getType();

            if ("required".equals(type)) {
                // If the type is required remove
                mutatedActions.remove(i);
                break;
            }

            i--;
        }
        return mutatedActions;
    }

    /**
     * @return the formActions
     */
    @Override
    public List<Action> actions() {
        return formActions;
    }

    /**
     * @param formActions the formActions to set
     */
    @Override
    public void setFormActions(List<Action> formActions) {
        this.formActions = formActions;
    }

    public List<Action> copyActions() {
        List<Action> mutatedActions = new ArrayList<>();

        for (Action a : formActions) {
            mutatedActions.add(new Action(a));
        }
        return mutatedActions;
    }

}
