import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NumberSubmitComponent } from './number-submit.component';

describe('NumberSubmitComponent', () => {
  let component: NumberSubmitComponent;
  let fixture: ComponentFixture<NumberSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NumberSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NumberSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
