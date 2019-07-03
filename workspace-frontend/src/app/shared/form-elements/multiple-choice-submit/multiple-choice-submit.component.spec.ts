import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MultipleChoiceSubmitComponent } from './multiple-choice-submit.component';

describe('MultipleChoiceSubmitComponent', () => {
  let component: MultipleChoiceSubmitComponent;
  let fixture: ComponentFixture<MultipleChoiceSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MultipleChoiceSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MultipleChoiceSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
