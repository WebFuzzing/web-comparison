package generator.mutations;

import parsers.model.files.graph.elements.Path;
import utils.RandomUtil;

/**
 * Receive a path
 * and remove some of the steps
 * A mutation in a test case means that we will try to execute
 * a test case with invalid sequence or combination of GUI components
 *
 * @author raphael
 */
public class PathMutation {

    private final Path path;

    public PathMutation(Path path) {
        this.path = path;
    }

    /**
     * Change the order of Steps in Path
     *
     * @return
     */
    public Path changeOrder() {
        Path mutatedPath = this.path;
        Object aux;

        for (Object o : mutatedPath.getSteps()) {
            int[] randoms = RandomUtil.generate2Nums(0, mutatedPath.getSteps().size() - 1);
            int randomNum = randoms[0];
            int randomNum2 = randoms[1];

            aux = mutatedPath.getStep(randomNum);
            mutatedPath.getSteps().set(randomNum, mutatedPath.getStep(randomNum2));
            mutatedPath.getSteps().set(randomNum2, aux);
        }
        return path;
    }


    //TODO
    public Path repeatAction() {
        return this.path;
    }

    //TODO
    public Path injectEvent() {
        return this.path;
    }


}

