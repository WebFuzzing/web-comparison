/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package generator.mutations;

import parsers.model.files.graph.elements.Action;

import java.util.List;

/**
 * @author raphael
 */
public interface IFormMutation {

    /**
     * @return the form_actions
     */
    List<Action> actions();

    List<Action> lapse();

    List<Action> lapseAction(Action action);

    List<Action> slipAction(Action action);

    String mistake(String value, int length, int gonnaFail);

    /**
     * @param formActions the form_actions to set
     */
    void setFormActions(List<Action> formActions);

    List<Action> slip();

    List<Action> removeRequiredInput();
}
