import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FeedbackFormCreationComponent } from './feedback-form-creation.component';

describe('FeedbackFormCreationComponent', () => {
  let component: FeedbackFormCreationComponent;
  let fixture: ComponentFixture<FeedbackFormCreationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FeedbackFormCreationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FeedbackFormCreationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
