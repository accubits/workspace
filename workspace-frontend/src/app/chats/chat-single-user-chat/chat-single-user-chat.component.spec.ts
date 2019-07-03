import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatSingleUserChatComponent } from './chat-single-user-chat.component';

describe('ChatSingleUserChatComponent', () => {
  let component: ChatSingleUserChatComponent;
  let fixture: ComponentFixture<ChatSingleUserChatComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatSingleUserChatComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatSingleUserChatComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
