<?php
namespace Data;


/**
* Example additional data to the output
*/
class Data {


    public function content($pageuri) {


        $pages = \Processwire\wire('pages')->get('/' . $pageuri);
//        var_dump($pages);
//        foreach ($pages as $page) {
//            $output[$page-id] = Data::getFields($page);
//
//        }

//        foreach($p->template->fieldgroup as $field) {
//            if($field->type instanceof \Processwire\FieldtypeFieldsetOpen) continue;
//            $value = $p->get($field->name);
//            $output['content'][$field->name] = $field->type->sleepValue($p, $field, $value);
//        }
//
        $output['data'] = Data::getFields($pages);
//        $children = $pages->children();
//
//        foreach ($children as $child) {
//            //var_dump($child);
//            $output['kinings'][$child->id] = Data::getFields($child);
//
//
//        }

//        $output['child_count'] = $children->count;
//
//        foreach ($children as $key => $child) {
//            foreach($child->template->fieldgroup as $field) {
//                if($field->type instanceof \Processwire\FieldtypeFieldsetOpen) continue;
//                $value = $child->get($field->name);
//                $output['children'][$child->name][$field->name] = $field->type->sleepValue($child, $field, $value);
//            }
//        }



		return $output;
	}

	public function allPages() {
        $output['sites'] = [];
        $pages = \ProcessWire\wire('pages')->find("template!=admin, has_parent!=2, include=all");
        $index = 1;

        foreach ($pages as $key => $page) {
            foreach($page->template->fieldgroup as $field) {
                if($field->type instanceof \Processwire\FieldtypeFieldsetOpen) continue;
                $value = $page->get($field->name);
                $output['pages'][$index][$field->name] = $field->type->sleepValue($page, $field, $value);
                $output['pages'][$index]['id'] = $page->id;
                $output['pages'][$index]['url'] = $page->url;
            }
            $index++;
        }

        return $output;
    }

    protected function getFields($page) {

        //$output = [];
        foreach($page->template->fieldgroup as $field) {
            if($field->type instanceof \Processwire\FieldtypeFieldsetOpen) continue;
            $value = $page->get($field->name);
            $output['content'][$field->name] = $field->type->sleepValue($page, $field, $value);
            $output['meta'] = Array(
                instanceID => $page->instanceID,
                id => $page->id,
                name => $page->name,
                path => $page->path,
                status => $page->status,
                template => (Array)$page->template,
                parent => (Object)$page->parent,
                numChildren => $page->numChildren,
                sort => $page->sort,
                sortfield => $page->sortfield,
                created => $page->created,
                modified => $page->modified,
                published => $page->published,
                createdUser => $page->createdUser,
                modifiedUser => $page->modifiedUser,
                isLoaded => $page->isLoaded,
                outputFormatting => $page->outputFormatting
            );
            //var_dump($page->template);
        }

        $children = $page->children;
        $output['child_count'] = $children->count;

        if(count($children) != 0) {
            foreach ($children as $child) {
                $output['children'][$child->id] = Data::getFields($child);
            }
        }
        //var_dump($output);
        return $output;


//        public 'instanceID' => int 12
//  public 'id' => int 1001
//  public 'name' => string 'about' (length=5)
//  public 'path' => string '/about/' (length=7)
//  public 'status' => string '' (length=0)
//  public 'template' => string 'basic-page' (length=10)
//  public 'parent' => string '/' (length=1)
//  public 'numChildren' => int 2
//  public 'sort' => int 0
//  public 'sortfield' => string 'sort' (length=4)
//  public 'created' => string '2016-08-05 11:59:49 (5 hours ago)' (length=33)
//  public 'modified' => string '2016-08-05 11:59:49 (5 hours ago)' (length=33)
//  public 'published' => string '2016-08-05 11:59:49 (5 hours ago)' (length=33)
//  public 'createdUser' => string 'admin' (length=5)
//  public 'modifiedUser' => string 'admin' (length=5)
//  public 'isLoaded' => int 1
//  public 'outputFormatting' => int 1


    }

}