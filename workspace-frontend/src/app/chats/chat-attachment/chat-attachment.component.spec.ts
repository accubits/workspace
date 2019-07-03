import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatAttachmentComponent } from './chat-attachment.component';

describe('ChatAttachmentComponent', () => {
  let component: ChatAttachmentComponent;
  let fixture: ComponentFixture<ChatAttachmentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatAttachmentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatAttachmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
