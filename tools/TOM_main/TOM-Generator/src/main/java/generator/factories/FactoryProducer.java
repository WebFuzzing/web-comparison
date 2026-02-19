package generator.factories;

import utils.DefaultValues;

/**
 * Created by Marcelo Gonçalves
 */
public class FactoryProducer {

    private FactoryProducer() {
        throw new IllegalAccessError("Utility class");
    }

    public static GeneratorAbstractFactory getTestTypeFactory(DefaultValues.typeOfTests typeOfTests) {
        switch (typeOfTests) {
            case IRIT:
                return new IRITFactory();
            case WEB:
                return new WEBFactory();
            case JSON:
                return new JSONFactory();
            default:
                return null;
        }
    }

    public static AlgorithmsAbstractFactory getAlgFactory(DefaultValues.typeOfAlg typeOfAlg) {
        switch (typeOfAlg) {
            case DFS:
                return new DFSFactory();
            case BFS:
                return new BFSFactory();
            default:
                return null;
        }
    }

}

