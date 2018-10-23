/**
 * 
 */
package demo.model;

import java.io.File;
import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

public class ImgUploadReqModel extends BaseReqModel {

    private String mchid;
    private String title;
    private File file;

    public ImgUploadReqModel(String src_code, String key, String mchid, String title, File file) {
        super(src_code, key);
        this.mchid = mchid;
        this.title = title;
        this.file = file;
    }

    /**
     * @return the file
     */
    public File getFile() {
        return file;
    }

    public Map<String, String> toReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.src_code)) {
            paramMap.put("src_code", this.src_code);
        }
        if (StringUtils.isNotEmpty(this.mchid)) {
            paramMap.put("mchid", this.mchid);
        }
        if (StringUtils.isNotEmpty(this.title)) {
            paramMap.put("title", this.title);
        }

        makeReqParamMap(paramMap);

        return paramMap;
    }

}
