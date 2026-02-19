package generator.factories;

import generator.generators.Settings;
import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import generator.generators.CodeGeneratorIRIT;
import generator.generators.CodeGeneratorJSON;
import generator.generators.CodeGeneratorWEB;

import java.util.Map;

/**
 * Created by Marcelo Gonçalves
 */
public class IRITFactory extends GeneratorAbstractFactory {

    @Override
    public CodeGeneratorIRIT getGenerator(Map<String, Value> values, Map<String, Mutation> mutationsValues, String modelName, String pathFolder) {
        return new CodeGeneratorIRIT(values, mutationsValues, modelName, pathFolder);
    }

    @Override
    CodeGeneratorJSON getGenerator(String folderDest) {
        return null;
    }

    @Override
    CodeGeneratorWEB getGenerator(Map<String, Value> values, Map<String, Mutation> mutations, Map<String, Mapping> mapping, Settings settings, String url) {
        return null;
    }
}
