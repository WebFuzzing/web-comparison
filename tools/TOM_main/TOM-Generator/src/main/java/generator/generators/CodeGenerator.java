package generator.generators;

import parsers.model.files.graph.elements.Path;
import utils.DefaultValues;

import java.util.List;

/**
 * Created by Marcelo Gonçalves
 */
public interface CodeGenerator {

    void testsGeneration(List<Path> paths, List<DefaultValues.typeOfMutations> testTypes) throws OutOfMemoryError;

    int getNumberOfTestsGenerated();

    int getNumberOfTestsMutated();
}
