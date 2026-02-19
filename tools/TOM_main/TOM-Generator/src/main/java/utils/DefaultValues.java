package utils;

import generator.StartGenerator;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import static utils.DefaultValues.typeOfMutations.NORMAL;

/**
 * Created by Marcelo Gonçalves
 */
public class DefaultValues {

    public static final Class<StartGenerator> APP_CLASS = StartGenerator.class;

    /**
     * ------------------------------------------ Generation Setup -----------------------------------------------------
     */

    // Type of Algorithm to use: BFS or DFS
    public static final DefaultValues.typeOfAlg algType = typeOfAlg.BFS;

    // Algorithm Setup
    public static final int MAX_VERTEX_VISIT = 1;
    public static final int MAX_EDGE_VISIT = 1;

    // Type of tests to generate: WEB, IRIT or JSON
    public static final DefaultValues.typeOfTests testType = typeOfTests.WEB;

    // Type of generated test cases
    // Put just the ones you want
    public static final List<typeOfMutations> testTypesToGenerate = new ArrayList<>(Arrays.asList(
            NORMAL,
            typeOfMutations.LAPSE_CALL,
            typeOfMutations.SLIP_CALL,
            typeOfMutations.LAPSE_FORM,
            typeOfMutations.MISTAKE_MUTATION,
            typeOfMutations.DOUBLE_CLICK_MUTATION,
            typeOfMutations.REMOVE_REQUIRED_FIELD,
            typeOfMutations.DOUBLE_CLICK_CALL,
            typeOfMutations.DOUBLE_CLICK_MENU_CALL,
            typeOfMutations.INJECT_BACK_EVENT,
            typeOfMutations.INJECT_REFRESH_EVENT,
            typeOfMutations.MUTATIONS_FROM_FILE
    ));

    // Type of MODEL to use: XML or EMDL
    public static final DefaultValues.typeOfParser modelType = typeOfParser.XML;

    /**
     * ------------------------------------------ Needed Files -----------------------------------------------------
     */

    // Destination folder to the test cases
    public static final String FOLDER_GEN = "";

    // Model of the SUT
    public static final String FILE_MODEL = "";

    // Input VALUES to the forms
    public static final String FILE_VALUES = "";

    // File with the desired MUTATIONS
    public static final String FILE_MUTATIONS = "";

    // From here down Only required if testType is WEB
    // File with the MAPPING
    public static final String FILE_MAP = "";

    // URL for the SUT
    public static final String URL = "";

    // Browser to use: Chrome: 1; Firefox: 2; Safari: 3; Opera: 4
    public static final int BROWSER = 1;

    /** --------------------------------------------- Do not edit from here down ---------------------------------------------------------------- */

    /**
     * Type of Algorithms
     */
    public enum typeOfParser {
        XML,
        EMDL
    }

    /**
     * Type of Algorithms
     */
    public enum typeOfAlg {
        DFS,
        BFS
    }

    /**
     * Type of Tests
     */
    public enum typeOfTests {
        IRIT,
        WEB,
        JSON
    }

    /**
     * Type of Mutations
     */
    public enum typeOfMutations {
        NORMAL,
        LAPSE_CALL,
        SLIP_CALL,
        LAPSE_FORM,
        MISTAKE_MUTATION,
        DOUBLE_CLICK_MUTATION,
        REMOVE_REQUIRED_FIELD,
        DOUBLE_CLICK_CALL,
        DOUBLE_CLICK_MENU_CALL,
        INJECT_BACK_EVENT,
        INJECT_REFRESH_EVENT,
        MUTATIONS_FROM_FILE
    }

    /** ------------------------------------------------------------------------------------------------------------- */

    // Previous MODEL to calculate the difference
    public static final String PREVIOUS_MODEL = "";

    // Boolean exists or not a previous MODEL file
    public static final boolean HAS_PREVIOUS = false;

}
