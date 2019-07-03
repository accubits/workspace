import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SingleLineComponent } from './single-line.component';

describe('SingleLineComponent', () => {
  let component: SingleLineComponent;
  let fixture: ComponentFixture<SingleLineComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SingleLineComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SingleLineComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
