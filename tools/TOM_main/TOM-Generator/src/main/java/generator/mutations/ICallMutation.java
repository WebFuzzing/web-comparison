/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package generator.mutations;

import parsers.model.files.graph.elements.Call;
import parsers.model.files.graph.elements.Interaction;

import java.util.List;

/**
 * @author raphael
 */
public interface ICallMutation {

    /**
     * Set the type of interaction a double click in a menu interaction
     *
     * @param steps
     * @return
     */
    List<Interaction> randomDoubleClickMenu(List<Interaction> steps);

    /**
     * Convert a click in a double click
     *
     * @param call
     */
    void doubleClick(Call call);

}
