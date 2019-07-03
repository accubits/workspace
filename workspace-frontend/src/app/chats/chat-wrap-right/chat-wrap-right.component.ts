import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';

@Component({
  selector: 'app-chat-wrap-right',
  templateUrl: './chat-wrap-right.component.html',
  styleUrls: ['./chat-wrap-right.component.scss']
})
export class ChatWrapRightComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
