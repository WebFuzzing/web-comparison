package generator.factories;

import generator.generators.CodeGeneratorIRIT;
import generator.generators.Settings;
import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import generator.generators.CodeGeneratorJSON;
import generator.generators.CodeGeneratorWEB;

import java.util.Map;

/**
 * Created by mgonc on 22/02/17.
 */
public abstract class GeneratorAbstractFactory {

    abstract CodeGeneratorIRIT getGenerator(Map<String, Value> values, Map<String, Mutation> mutationsValues, String modelName, String pathFolder);

    abstract CodeGeneratorJSON getGenerator(String folderDest);

    abstract CodeGeneratorWEB getGenerator(Map<String, Value> values, Map<String, Mutation> mutations, Map<String, Mapping> mapping, Settings settings, String url);

}
