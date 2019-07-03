import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TarWrapComponent } from './tar-wrap.component';

describe('TarWrapComponent', () => {
  let component: TarWrapComponent;
  let fixture: ComponentFixture<TarWrapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TarWrapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TarWrapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
