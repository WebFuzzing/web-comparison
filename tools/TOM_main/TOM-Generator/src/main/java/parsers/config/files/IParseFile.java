package parsers.config.files;

import java.io.IOException;
import java.util.Map;

/**
 * @author raphaelrodrigues
 */
@FunctionalInterface
public interface IParseFile {

    /**
     * Get the VALUES from the file configurations
     *
     * @param path
     * @return
     * @throws IOException
     */
    Map<String, ?> getValues(String path) throws IOException;

}
