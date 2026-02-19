package generator.factories;

import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import generator.generators.CodeGeneratorIRIT;
import generator.generators.CodeGeneratorJSON;
import generator.generators.CodeGeneratorWEB;
import generator.generators.Settings;

import java.util.Map;

/**
 * Created by Marcelo Gonçalves
 */
public class WEBFactory extends GeneratorAbstractFactory {

    @Override
    public CodeGeneratorWEB getGenerator(Map<String, Value> values, Map<String, Mutation> mutations, Map<String, Mapping> mapping, Settings settings, String url) {
        return new CodeGeneratorWEB(values, mutations, mapping, settings, url);
    }

    @Override
    CodeGeneratorIRIT getGenerator(Map<String, Value> values, Map<String, Mutation> mutationsValues, String modelName, String pathFolder) {
        return null;
    }

    @Override
    CodeGeneratorJSON getGenerator(String folderDest) {
        return null;
    }
}
