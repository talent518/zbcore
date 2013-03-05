{php $page=M('page')->get_by_where('cat_id='.$catid.' AND (title=\''.addslashes($category['cat_name']).'\' OR page_name=\''.addslashes($category['cat_path']).'\')');}
{template page/view}