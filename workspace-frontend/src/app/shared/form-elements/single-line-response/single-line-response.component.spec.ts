import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SingleLineResponseComponent } from './single-line-response.component';

describe('SingleLineResponseComponent', () => {
  let component: SingleLineResponseComponent;
  let fixture: ComponentFixture<SingleLineResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SingleLineResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SingleLineResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
