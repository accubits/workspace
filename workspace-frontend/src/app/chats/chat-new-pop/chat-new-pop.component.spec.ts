import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatNewPopComponent } from './chat-new-pop.component';

describe('ChatNewPopComponent', () => {
  let component: ChatNewPopComponent;
  let fixture: ComponentFixture<ChatNewPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatNewPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatNewPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
