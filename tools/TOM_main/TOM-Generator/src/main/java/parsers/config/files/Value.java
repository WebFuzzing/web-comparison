package parsers.config.files;

import java.util.ArrayList;
import java.util.List;

/**
 * @author Marcelo Gonçalves
 */
public class Value {

    private String key;
    private List<String> values;

    public Value(String key, List<String> s) {
        this.key = key;
        this.values = s;
    }

    public Value(Value v) {
        this.key = v.getKey();
        this.values = v.getValues();
    }

    /**
     * @return the name_model
     */
    public String getKey() {
        return key;
    }

    /**
     * @return the value
     */
    public List<String> getValues() {
        return values;
    }

    /**
     * @param value the value to set
     */
    public void setValue(String value) {
        this.values = new ArrayList<>();
        this.values.add(value);
    }

    /**
     * @param key the key to set
     */
    public void setKey(String key) {
        this.key = key;
    }

    /**
     * @param value the value to set
     */
    public void addValue(String value) {
        this.values.add(value);
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("\"{\n \t\"" + key + "\": [\n");

        for (String s : values) {
            sb.append(s +"\n");
        }

        sb.append("\n\t]\n}\n");
        return sb.toString();
    }

    @Override
    public Value clone() {
        StringBuilder sb = new StringBuilder();
        sb.append("\"{\n \t\"" + key + "\": [\n");

        Value value = new Value(key, new ArrayList<>());

        for (String s : values) {
            value.addValue(s);
        }

        return value;
    }

}
