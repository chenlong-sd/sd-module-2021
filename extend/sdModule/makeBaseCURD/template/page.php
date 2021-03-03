/**
 * //=={Table}==//.php
 * Date: //=={date}==//
 * User: chenlong <vip_chenlong@163.com>
 */

namespace //=={namespace}==//;

use app\common\BasePage;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layui\defaultForm\Form as DefaultForm;
//=={use}==//


/**
 * Class //=={Table}==//
 * @package //=={namespace}==//\//=={Table}==//
 */
class //=={Table}==// extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return TablePage
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            //=={table_page}==//
        ]);

        $table->setHandleWidth(150);
        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return DefaultForm
    * @throws \ReflectionException
    * @throws \app\common\SdException
    */
    public function formData(string $scene, array $default_data = []): DefaultForm
    {
        $unit = [
            //=={form_data}==//
        ];

        $form = DefaultForm::create($unit)->setDefaultData($default_data);

        return $form->complete();
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return "//=={page_name}==//";
    }

    /**
     * 创建搜索表单的数据
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData(): DefaultForm
    {
        $form_data = [//=={search_form}==//];
        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }

}
