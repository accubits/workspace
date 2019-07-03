import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SingleLineSubmitComponent } from './single-line-submit.component';

describe('SingleLineSubmitComponent', () => {
  let component: SingleLineSubmitComponent;
  let fixture: ComponentFixture<SingleLineSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SingleLineSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SingleLineSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
