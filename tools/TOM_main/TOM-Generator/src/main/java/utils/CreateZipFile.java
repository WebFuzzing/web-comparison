package utils;

import org.apache.commons.io.FileUtils;
import org.apache.commons.io.IOUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.zip.ZipEntry;
import java.util.zip.ZipOutputStream;

/**
 * Created by Marcelo Gonçalves
 */
public class CreateZipFile {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private List<String> fileList;
    private final String output;
    private final String source;
    private final long id;

    public CreateZipFile(String source, String output, long id) {
        this.fileList = new ArrayList<>();
        this.output = output;
        this.source = source;
        this.id = id;
    }

    public void createZip() {
        generateFileList(new File(source));
        zipIt(output + id + ".zip");
    }

    /**
     * Zip it
     *
     * @param zipFile output ZIP file location
     */
    private void zipIt(String zipFile) {

        byte[] buffer = new byte[1024];
        FileInputStream in = null;
        ZipOutputStream zos = null;
        FileOutputStream fos = null;
        try {

            fos = new FileOutputStream(zipFile);
            zos = new ZipOutputStream(fos);

            for (String file : this.fileList) {

                ZipEntry ze = new ZipEntry(file);
                zos.putNextEntry(ze);

                in = new FileInputStream(source + File.separator + file);

                int len;
                while ((len = in.read(buffer)) > 0) {
                    zos.write(buffer, 0, len);
                }

                in.close();
            }

            FileUtils.deleteDirectory(new File(source));

        } catch (IOException ex) {
            logger.error(ex.getMessage());
        } finally {
            IOUtils.closeQuietly(in);
            IOUtils.closeQuietly(zos);
            IOUtils.closeQuietly(fos);
        }
    }

    /**
     * Traverse a directory and get all files,
     * and add the file into fileList
     *
     * @param node file or directory
     */
    private void generateFileList(File node) {

        // Add file only
        if (node.isFile()) {
            fileList.add(generateZipEntry(node.getAbsoluteFile().toString()));
        }

        if (node.isDirectory()) {
            String[] subNote = node.list();
            for (String filename : subNote) {
                generateFileList(new File(node, filename));
            }
        }
    }

    /**
     * Format the file path for zip
     *
     * @param file file path
     * @return Formatted file path
     */
    private String generateZipEntry(String file) {
        return file.substring(source.length(), file.length());
    }

}