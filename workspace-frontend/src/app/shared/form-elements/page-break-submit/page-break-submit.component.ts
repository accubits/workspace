import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-page-break-submit',
  templateUrl: './page-break-submit.component.html',
  styleUrls: ['./page-break-submit.component.scss']
})
export class PageBreakSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data model for checkbox element */
  pageElement =         {
    type: 'page',
    componentId: null,
    page: {
      formPageSlug: '',
      title: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.pageElement = this.data;
     }, 100)
  }

  goToPrevious():void{
    let index = this.dataService.viewForm.pageSlugs.indexOf(this.dataService.viewForm.selectdPageSlug);
    this.dataService.viewForm.selectdPageSlug = this.dataService.viewForm.pageSlugs[index - 1];
    this.dataService.viewForm.selectedPage[this.dataService.viewForm.selectdPageSlug] = this.dataService.viewForm.selectedFormDetails.formPages[this.dataService.viewForm.selectdPageSlug].formComponents
 

  }

  goToNext():void{
    let index = this.dataService.viewForm.pageSlugs.indexOf(this.dataService.viewForm.selectdPageSlug);
    this.dataService.viewForm.selectdPageSlug = this.dataService.viewForm.pageSlugs[index + 1];
    this.dataService.viewForm.selectedPage[this.dataService.viewForm.selectdPageSlug] = this.dataService.viewForm.selectedFormDetails.formPages[this.dataService.viewForm.selectdPageSlug].formComponents
  }


}
